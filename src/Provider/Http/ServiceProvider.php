<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 10.02.2017
 * Time: 20:09
 */

namespace Tallanto\Api\Provider\Http;


use Tallanto\Api\Provider\AbstractProvider;

use Tallanto\Api\Provider\ProviderInterface;

class ServiceProvider extends AbstractProvider implements ProviderInterface {

  /**
   * @var Request
   */
  protected $request;

  /**
   * @var integer
   */
  protected $total_count;

  /**
   * ServiceProvider constructor.
   *
   * @param Request $request Request object to use
   */
  public function __construct($request) {
    $this->request = $request;
  }

  /**
   * Fetches (loads) data from the upstream using page number, page size and
   * possible ID and query values.
   *
   * Returns array if everything is OK.
   *
   * @return array
   */
  function fetch() {
    $result = $this->request
      ->setParameters([
        'page_number' => $this->getPageNumber(),
        'page_size'   => $this->getPageSize(),
        'total_count' => 'true',
        'q'           => $this->query,
      ])
      ->get();
    // Parse HTTP headers
    $this->parseHeaders($this->request->getHeaders());
    /*if ($this->logger) {
      $this->logger->debug('Headers', ['headers' => print_r($this->request->getHeaders(), TRUE)]);
    }*/
    return $result;
  }

  /**
   * Parse headers received from the REST service.
   *
   * @param array $headers
   */
  protected function parseHeaders($headers) {
    foreach ($headers as $header) {
      // Find total count header
      if (preg_match('/X-Total-Count: ([0-9]+)/', $header, $matches) && isset($matches[1])) {
        $this->total_count = $matches[1];
      }
    }
  }

  /**
   * @inheritdoc
   */
  function totalCount() {
    if (is_null($this->total_count)) {
      throw new \Exception('Unable to tell total records count prior to fetch() method.');
    }
    return $this->total_count;
  }

}