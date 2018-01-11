<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 18.01.2017
 * Time: 18:34
 */

namespace Tallanto\Api\Provider\Http;


use Psr\Log\LoggerInterface;
use Tallanto\Api\Exception\HttpException;
use Tallanto\Api\Exception\JsonException;

class Request {

  /**
   * @var LoggerInterface
   */
  protected $logger;

  /**
   * @var resource
   */
  protected $handler;

  /**
   * @var string
   */
  protected $url;

  /**
   * @var string
   */
  protected $method;

  /**
   * @var string
   */
  protected $login;

  /**
   * @var string
   */
  protected $api_hash;

  /**
   * @var array
   */
  protected $parameters = [];

  /**
   * @var array
   */
  protected $request_headers = [];

  /**
   * @var array
   */
  protected $response_headers = [];

  /**
   * @var int
   */
  protected $last_http_code = 0;

  /**
   * Request constructor.
   */
  public function __construct() {
    $this->request_headers['Accept'] = 'application/json';
  }

  /**
   * Sets logger.
   *
   * @param  LoggerInterface $logger
   * @return Request
   */
  public function setLogger(LoggerInterface $logger) {
    $this->logger = $logger;

    return $this;
  }

  /**
   * Send HTTP GET request to server and return response
   *
   * @return mixed|null
   */
  public function get() {
    return $this->execute('GET');
  }

  /**
   * Execute cURL
   *
   * @param string $http_method HTTP method 'GET' or 'POST'
   * @param array $params
   * @return mixed|null
   * @throws \Exception
   */
  protected function execute($http_method, $params = []) {
    $result = NULL;
    $handler = $this->getHandler();

    if ('post' == strtolower($http_method)) {
      curl_setopt($handler, CURLOPT_POSTFIELDS, http_build_query($params));
    }
    $curl_result = curl_exec($handler);
    $http_code = curl_getinfo($handler, CURLINFO_HTTP_CODE);
    $this->last_http_code = $http_code;

    // Add debug log
    /*if ($this->logger) {
      $this->logger->debug('cURL raw response', [
        'raw_response' => $curl_result,
      ]);
    }*/

    // Check for general error
    if (FALSE === $curl_result) {
      throw new HttpException(curl_error($handler));
    }

    // Split headers and body
    list($head, $body) = explode("\r\n\r\n", $curl_result, 2);
    $headers = explode("\r\n", $head);
    $this->response_headers = [];
    foreach ($headers as $header_item) {
      if (preg_match('/^([^:]+):\s?(.+)\s?$/i', $header_item, $matches) &&
        isset($matches[1]) && isset($matches[2])
      ) {
        $this->response_headers[$matches[1]] = $matches[2];
      }
    }

    // Add debug log
    if ($this->logger) {
      $this->logger->debug('Server returned HTTP code {http_code}', [
        'http_code'        => $http_code,
        'post_fields'      => ('post' ==
          strtolower($http_method)) ? substr(print_r($params, TRUE), 0,
          2048) : NULL,
        'response_headers' => print_r($this->response_headers, TRUE),
        'response_body'    => substr(print_r($body, TRUE), 0, 2048),
      ]);
    }

    // Check HTTP code
    if ((200 != $http_code) && (204 != $http_code)) {
      throw new HttpException(sprintf('Server returned HTTP code %d for URI "%s"',
        $http_code, $this->getUri()));
    } elseif (200 == $http_code) {
      // Try to decode JSON
      $result = json_decode($body, TRUE);
      if (json_last_error() != JSON_ERROR_NONE) {
        throw new JsonException('Error decoding JSON: '.json_last_error_msg());
      }
    }

    $this->closeHandler();

    return $result;
  }

  /**
   * Get cURL handler
   */
  public function getHandler() {
    if (!is_null($this->handler)) {
      return $this->handler;
    }

    $uri = $this->getUri();

    // Set up request options
    $this->handler = curl_init();
    curl_setopt($this->handler, CURLOPT_URL, $uri);
    curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($this->handler, CURLOPT_USERAGENT, 'tallanto-api-client/1.0');
    $headers = [];
    foreach ($this->request_headers as $header_name => $header_value) {
      $headers[] = $header_name.': '.$header_value;
    }
    curl_setopt($this->handler, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($this->handler, CURLOPT_HEADER, TRUE);
    curl_setopt($this->handler, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($this->handler, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($this->handler, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($this->handler, CURLOPT_USERPWD,
      sprintf('%s:%s', $this->getLogin(), $this->getApiHash()));

    if ($this->logger) {
      $this->logger->debug('Created cURL handler with URI "{uri}"', [
        'uri'     => $uri,
        'headers' => $this->request_headers,
      ]);
    }

    return $this->handler;
  }

  /**
   * Get fully qualified URI for request
   *
   * @return string
   */
  public function getUri() {
    // Build query string
    $params = '';
    foreach ($this->parameters as $key => $value) {
      $params .= empty($params) ? '?' : '&';
      $params .= urlencode($key).'='.urlencode($value);
    }
    // Build URI
    $uri = sprintf('%s%s%s', $this->getUrl(), $this->getMethod(), $params);

    return $uri;
  }

  /**
   * Get URL, for example 'http://subdomain.example.com'
   *
   * @return string
   */
  public function getUrl() {
    return $this->url;
  }

  /**
   * Set URL, for example 'http://subdomain.example.com'
   *
   * @param string $url
   * @return Request
   */
  public function setUrl($url) {
    $this->url = $url;

    return $this;
  }

  /**
   * Get API method, for example '/private/api/v2/json/contacts/list'
   *
   * @return string
   */
  public function getMethod() {
    return $this->method;
  }

  /**
   * Set API method, for example '/private/api/v2/json/contacts/list'
   *
   * @param string $method
   * @return Request
   */
  public function setMethod($method) {
    $this->method = $method;

    return $this;
  }

  /**
   * Get user login
   *
   * @return string
   */
  public function getLogin() {
    return $this->login;
  }

  /**
   * Set user login
   *
   * @param string $login
   * @return Request
   */
  public function setLogin($login) {
    $this->login = $login;

    return $this;
  }

  /**
   * Get API hash
   *
   * @return string
   */
  public function getApiHash() {
    return $this->api_hash;
  }

  /**
   * Set API hash
   *
   * @param string $api_hash
   * @return Request
   */
  public function setApiHash($api_hash) {
    $this->api_hash = $api_hash;

    return $this;
  }

  /**
   * Close handler
   */
  public function closeHandler() {
    curl_close($this->handler);
    $this->handler = NULL;

    $this->logger->debug('cURL handler closed');
  }

  /**
   * Send HTTP POST request to amoCRM server and return response
   *
   * @param array $params
   * @return mixed|null
   */
  public function post($params) {
    return $this->execute('POST', $params);
  }

  /**
   * @return array
   */
  public function getParameters() {
    return $this->parameters;
  }

  /**
   * @param array $parameters
   * @return Request
   */
  public function setParameters($parameters) {
    $this->parameters = $parameters;

    return $this;
  }

  /**
   * @return array
   */
  public function getResponseHeaders() {
    return $this->response_headers;
  }

  /**
   * @param string $header_name
   * @param string $header_value
   * @return Request
   */
  public function setRequestHeader($header_name, $header_value) {
    $this->request_headers[$header_name] = $header_value;

    return $this;
  }

  /**
   * @return array
   */
  public function getRequestHeaders() {
    return $this->request_headers;
  }

  /**
   * @return int
   */
  public function getLastHttpCode() {
    return $this->last_http_code;
  }

}