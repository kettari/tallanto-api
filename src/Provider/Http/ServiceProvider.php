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
    $result = $this->request->setParameters([
      'page_number' => $this->getPageNumber(),
      'page_size'   => $this->getPageSize(),
      'total_count' => 'true',
      'q'           => $this->query,
    ])
      ->get();

    // To be parsed correctly, $result should not be NULL
    if (is_null($result)) {
      $result = [];
    }

    // Find total count header
    $response_headers = $this->request->getResponseHeaders();
    if (isset($response_headers['X-Total-Count'])) {
      $this->total_count = $response_headers['X-Total-Count'];
    } else {
      $this->total_count = NULL;
    }

    return $result;
  }

  /**
   * Fetches all rows from the upstream.
   *
   * Returns array if everything is OK.
   *
   * @return array
   */
  public function fetchAll() {
    $result = [];
    $this->setPageNumber(1);
    do {
      $part = $this->fetch();
      if (is_array($part) && (count($part) > 0)) {
        $result = array_merge($result, $part);
      }
      $this->setPageNumber($this->getPageNumber() + 1);
    } while (($this->total_count > 0) && (count($result) < $this->total_count));

    return $result;
  }


  /**
   * @inheritdoc
   */
  function totalCount() {
    return $this->total_count;
  }

}