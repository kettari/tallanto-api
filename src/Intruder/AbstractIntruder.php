<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 21.02.2017
 * Time: 18:44
 */

namespace Tallanto\Api\Intruder;


use Psr\Log\LoggerInterface;
use Tallanto\Api\Exception\ConflictHttpException;
use Tallanto\Api\Exception\HeaderNotFoundException;
use Tallanto\Api\Exception\HttpException;
use Tallanto\Api\Exception\InvalidHeaderException;
use Tallanto\Api\Exception\OperationNotAuthorizedException;


/**
 * Class AbstractIntruder
 *
 * Used for update operations at Tallanto server, behaving like a user.
 *
 * @package Tallanto\Api\AbstractIntruder
 */
abstract class AbstractIntruder {

  /**
   * User agent string
   */
  const USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36';

  /**
   * Session cookie name
   */
  const SESSION_COOKIE_NAME = 'PHPSESSID';

  protected $host;
  protected $endpoint = '/index.php';
  protected $user_name;
  protected $password;

  /**
   * @var  LoggerInterface
   */
  protected $logger;

  /**
   * @var int
   */
  protected $last_http_code = 0;

  /**
   * @var array
   */
  protected $last_cookies = [];

  /**
   * @var string
   */
  protected $last_content;

  /**
   * @var array
   */
  protected $last_headers = [];

  /**
   * @var int
   */
  protected $last_error_number;

  /**
   * @var string
   */
  protected $last_error_message;

  /**
   * @var string
   */
  protected $session_cookie;

  /**
   * @var bool
   */
  protected $is_logged = FALSE;

  /**
   * @var bool
   */
  protected $is_debug = FALSE;

  /**
   * Authorizes at the Tallanto server.
   *
   * @return AbstractIntruder
   */
  public function login() {
    $this->last_cookies = [];
    $url = $this->getUrl();
    $this->retrieveResource($url, [$this, 'setOptLogin']);
    $this->is_logged = $this->isLastOperationAuthorized();
    if ($this->is_logged &&
      isset($this->last_cookies[self::SESSION_COOKIE_NAME])
    ) {
      $this->session_cookie = $this->last_cookies[self::SESSION_COOKIE_NAME];
    } else {
      $this->session_cookie = NULL;
    }

    // Add log message
    if (!is_null($this->logger)) {
      $this->logger->debug('Attempted to log in to the Tallanto at "{url}" with result: {login_result}',
        [
          'url'          => $url,
          'login_result' => ($this->is_logged) ? 'success' : 'failure',
        ]);
    }

    return $this;
  }

  /**
   * Sets cURL option to send POST login credentials.
   *
   * @param resource $handler
   * @param mixed $data
   */
  protected function setOptLogin($handler, /** @noinspection PhpUnusedParameterInspection */
                                 $data) {
    curl_setopt($handler, CURLOPT_POST, TRUE);
    curl_setopt($handler, CURLOPT_POSTFIELDS,
      'module=Users&action=Authenticate&return_module=Users&return_action=Login&'.
      sprintf('user_name=%s&user_password=%s', urlencode($this->getUserName()),
        urlencode($this->getPassword())));

    // Add log message
    if (!is_null($this->logger)) {
      $this->logger->debug('setOptLogin()');
    }
  }

  /**
   * Checks if last operation attempt was authorized by server.
   *
   * @return bool
   */
  protected function isLastOperationAuthorized() {
    $location = isset($this->last_headers['Location']) ? $this->last_headers['Location'] : '';
    if ((302 == $this->last_http_code) &&
      (FALSE !== strpos($location, 'action=Login'))
    ) {
      return FALSE;
    }

    return TRUE;
  }

  /**
   * Returns TRUE if AbstractIntruder is currently logged in.
   *
   * @return bool
   */
  public function isLoggedIn() {
    $this->retrieveResource($this->getUrl());
    $this->is_logged = (200 == $this->last_http_code);

    return $this->is_logged;
  }

  /**
   * Retrieves resource specified by the URL.
   *
   * @param $url
   * @param mixed $curl_setopt_callback
   * @param mixed $callback_data
   * @return void
   * @throws \Exception
   */
  protected function retrieveResource($url, callable $curl_setopt_callback = NULL, $callback_data = NULL) {
    try {
      // Debug part
      $debug = NULL;
      $out = NULL;
      if ($this->is_debug) {
        $out = fopen('php://temp', 'rw+');
      }

      // Create and configure cURL
      $handler = curl_init();
      if ($this->is_debug) {
        curl_setopt($handler, CURLOPT_VERBOSE, TRUE);
        curl_setopt($handler, CURLOPT_STDERR, $out);
      }
      curl_setopt($handler, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($handler, CURLOPT_USERAGENT, self::USER_AGENT);
      curl_setopt($handler, CURLOPT_URL, $url);
      curl_setopt($handler, CURLOPT_HEADER, TRUE);
      curl_setopt($handler, CURLOPT_CONNECTTIMEOUT, 100);
      curl_setopt($handler, CURLOPT_TIMEOUT, 120);
      $cookies = [];
      foreach ($this->last_cookies as $key => $value) {
        if (self::SESSION_COOKIE_NAME == $key) {
          continue;
        }
        $cookies[] = $key.'='.$value;
      }
      if (!is_null($this->session_cookie)) {
        $cookies[] = self::SESSION_COOKIE_NAME.'='.$this->session_cookie;
      }
      $cookies = implode(';', $cookies);
      curl_setopt($handler, CURLOPT_COOKIE, $cookies);

      // Invoke callback to set some options
      if (is_callable($curl_setopt_callback)) {
        call_user_func_array($curl_setopt_callback, [$handler, $callback_data]);
      }

      // Execute handler and parse response
      $this->last_content = curl_exec($handler);

      // Debug part
      if ($this->is_debug) {
        rewind($out);
        $debug = stream_get_contents($out);
        fclose($out);
      }

      $this->last_http_code = curl_getinfo($handler, CURLINFO_HTTP_CODE);
      $this->last_error_number = curl_errno($handler);
      $this->last_error_message = curl_error($handler);
      $this->last_headers = $this->getHeadersFromCurlResponse($this->last_content);
      $this->last_cookies = $this->getCookiesFromCurlResponse($this->last_content);

      // Add log message
      if (0 == $this->last_error_number) {
        $this->logger->debug('Executed cURL "{url}" with HTTP code {http_code}', [
          'url'              => $url,
          'http_code'        => $this->last_http_code,
          'headers'          => $this->last_headers,
          'cookies'          => $this->last_cookies,
        ]);
      } else {
        $this->logger->warning('Executed cURL "{url}" with cURL code {curl_err_num} "{curl_err_message}"',
          [
            'url'              => $url,
            'http_code'        => $this->last_http_code,
            'curl_err_num'     => $this->last_error_number,
            'curl_err_message' => $this->last_error_message,
            'headers'          => $this->last_headers,
            'cookies'          => $this->last_cookies,
          ]);
      }
      if ($this->is_debug) {
        $this->logger->debug('cURL debug', [
          'verbose' => $debug,
          'content' => $this->last_content,
        ]);
      }

      // Throw exception if got 4xx or 5xx from the server
      if ($this->last_http_code >= 400) {
        throw new HttpException('Tallanto server returned HTTP error code '.
          $this->last_http_code, $this->last_http_code);
      }

    } catch (\Exception $e) {
      if (!is_null($this->logger)) {
        $message = (!empty($this->last_error_message)) ? $this->last_error_message : $e->getMessage();
        $code = (!empty($this->last_error_number)) ? $this->last_error_number : $e->getCode();
        $this->logger->error('Failed with cURL: {error_message} (code {error_code})',
          [
            'error_message' => $message,
            'error_code'    => $code,
            'headers'       => $this->last_headers,
            'cookies'       => $this->last_cookies,
          ]);
        throw $e;
      }
    }

    return;
  }

  /**
   * Extracts headers from cUrl response.
   *
   * @param string $response Result returned by curl_exec()
   * @return array
   */
  private function getHeadersFromCurlResponse($response) {
    $headers = [];

    $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));
    while (!empty($header_text)) {
      $response = substr($response, strlen($header_text) + 4);

      foreach (explode("\r\n", $header_text) as $i => $line) {
        if ($i === 0) {
          $headers['http_code'] = $line;
        } else {
          list ($key, $value) = explode(': ', $line);

          $headers[$key] = $value;
        }
      }

      $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));
    }

    return $headers;
  }

  /**
   * Extracts cookies from cUrl response.
   *
   * @param string $response Result returned by curl_exec()
   * @return array
   */
  private function getCookiesFromCurlResponse($response) {
    $cookies = [];

    preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $response, $matches);
    foreach ($matches[1] as $item) {
      parse_str($item, $cookie);
      $cookies = array_merge($cookies, $cookie);
    }

    return $cookies;
  }

  /**
   * Formats $fields array as multipart/form-data with specified boundary
   * token.
   *
   * @param array $fields Array of fields to format as multipart/form-data.
   *   Array format: [
   *     0 => [
   *       'name'   => 'name1',
   *       'value'  => 'value1',
   *       ],
   *     1 => [
   *       'name'   => 'name1',
   *       'value'  => 'value2',
   *       ],
   *     2 => [
   *       'name'   => 'name2',
   *       'value'  => 'value1',
   *       ],
   *     ]
   * @param string $boundary Boundary to use
   * @return string
   */
  protected function multipartBuildQuery($fields, $boundary) {
    $result = '';
    foreach ($fields as $item) {
      if (isset($item['name']) && isset($item['value'])) {
        $name = $item['name'];
        $value = $item['value'];
        $result .= "--$boundary\r\nContent-Disposition: form-data; name=\"$name\"\r\n\r\n$value\r\n";
      }
    }
    $result .= "--$boundary--";

    return $result;
  }

  /**
   * Generates random alphanumeric string.
   *
   * @param int $length
   * @return string
   */
  protected function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
  }

  /**
   * If Tallanto created the resource, retrieve its ID from the Location header
   *
   * @return string Identifier of the resource created or updated
   * @throws \Tallanto\Api\Exception\ConflictHttpException
   * @throws \Tallanto\Api\Exception\HeaderNotFoundException
   * @throws \Tallanto\Api\Exception\InvalidHeaderException
   * @throws \Tallanto\Api\Exception\OperationNotAuthorizedException
   */
  protected function retrieveIdentifier() {
    if ($this->isLastOperationAuthorized()) {
      if (isset($this->last_headers['Location'])) {
        // Check for duplicates
        if (FALSE !==
          strpos($this->last_headers['Location'], 'action=ShowDuplicates')
        ) {
          throw new ConflictHttpException('Entity is not created: possible duplicates found.');
        }
        // Try to find record ID
        if (preg_match('/record=([0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12})/',
            $this->last_headers['Location'], $matches) && isset($matches[1])
        ) {
          if (!is_null($this->logger)) {
            $this->logger->info('Created entity in the Tallanto at "{url}" with ID: {id}',
              ['url' => $this->getUrl(), 'id' => $matches[1]]);
          }

          return $matches[1];
        } else {
          throw new InvalidHeaderException('Location header found but it is invalid: no entity ID is found.');
        }
      } else {
        throw new HeaderNotFoundException('Location header expected and was not found in the response from the Tallanto server.');
      }
    } else {
      throw new OperationNotAuthorizedException('Attempt to create new contact was not authorized by the Tallanto server.');
    }
  }

  /**
   * Validates data before posting to the Tallanto server. Throws errors if
   * found errors.
   *
   * @param array $data
   * @return void
   */
  abstract protected function validate($data);

  /**
   * Returns full URL.
   *
   * @return string
   */
  protected function getUrl() {
    return $this->host.$this->endpoint;
  }

  /**
   * @return mixed
   */
  public function getHost() {
    return $this->host;
  }

  /**
   * @param mixed $host
   * @return AbstractIntruder
   */
  public function setHost($host) {
    $this->host = $host;

    return $this;
  }

  /**
   * @return mixed
   */
  public function getUserName() {
    return $this->user_name;
  }

  /**
   * @param mixed $user_name
   * @return AbstractIntruder
   */
  public function setUserName($user_name) {
    $this->user_name = $user_name;

    return $this;
  }

  /**
   * @return mixed
   */
  public function getPassword() {
    return $this->password;
  }

  /**
   * @param mixed $password
   * @return AbstractIntruder
   */
  public function setPassword($password) {
    $this->password = $password;

    return $this;
  }

  /**
   * @param  LoggerInterface $logger
   * @return AbstractIntruder
   */
  public function setLogger($logger) {
    $this->logger = $logger;

    return $this;
  }

  /**
   * @param string $endpoint
   * @return AbstractIntruder
   */
  public function setEndpoint($endpoint) {
    $this->endpoint = $endpoint;

    return $this;
  }

  /**
   * @param bool $is_debug
   * @return AbstractIntruder
   */
  public function setDebug($is_debug) {
    $this->is_debug = $is_debug;

    return $this;
  }
}