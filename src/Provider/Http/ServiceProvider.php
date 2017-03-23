<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 10.02.2017
 * Time: 20:09
 */

namespace Tallanto\Api\Provider\Http;


use Tallanto\Api\ExpandableInterface;
use Tallanto\Api\ExpandableTrait;
use Tallanto\Api\Provider\AbstractProvider;
use Tallanto\Api\Provider\ProviderInterface;

class ServiceProvider extends AbstractProvider implements ProviderInterface, ExpandableInterface {

  use ExpandableTrait;

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
      'expand'      => ($this->isExpand()) ? 'true' : 'false',
    ])
      ->get();

    // To be parsed correctly, $result should not be NULL
    if (is_null($result)) {
      $result = [];
    }

    // Get total count of records
    $response_headers = $this->request->getResponseHeaders();
    $this->total_count = isset($response_headers['X-Total-Count']) ? $response_headers['X-Total-Count'] : 0;

    // Allow some debug
    $this->logger->debug('Fetched {result_count} items with total count = {total_count}',
      ['result_count' => count($result), 'total_count' => $this->total_count]);

    return $result;
  }

  /**
   * Fetches all rows from the upstream.
   *
   * Returns array if everything is OK.
   *
   * @param callable $callback Callback to invoke while fetching data.
   * @return array
   */
  public function fetchAll(callable $callback = NULL) {
    $result = [];
    $current_page = 0;

    // Iterate while everything is not loaded
    do {
      // Advance one page further
      $this->setPageNumber(++$current_page);

      // Call regular fetch() method
      $part = $this->fetch();
      $result = array_merge($result, $part);

      // Invoke callback when loading is in progress
      if (!is_null($callback)) {
        if (1 == $this->getPageNumber()) {
          call_user_func_array($callback, [
            'status'      => 'start',
            'data'        => $part,
            'total_count' => $this->total_count,
          ]);
        } else {
          call_user_func_array($callback, [
            'status'      => 'loading',
            'data'        => $part,
            'total_count' => $this->total_count,
          ]);
        }
      }

    } while (count($part) == $this->getPageSize());

    // Invoke callback when data is fully loaded
    if (!is_null($callback)) {
      call_user_func_array($callback, [
        'status'      => 'loaded',
        'data'        => $result,
        'total_count' => $this->total_count,
      ]);
    }

    return $result;
  }


  /**
   * @inheritdoc
   */
  function totalCount() {
    return $this->total_count;
  }

}