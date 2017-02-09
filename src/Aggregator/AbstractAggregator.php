<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 18.01.2017
 * Time: 18:43
 */

namespace Tallanto\Api\Aggregator;

use ArrayObject;

abstract class AbstractAggregator extends ArrayObject {

  /**
   * Clear items
   */
  public function clear() {
    $iterator = $this->getIterator();
    foreach ($iterator as $key => $item) {
      $iterator->offsetUnset($key);
    }
  }

  /**
   * Search for entities
   *
   * @param $query
   * @return array|null
   */
  abstract public function search($query);

  /**
   * Get entity by ID
   *
   * @param string $id
   * @return mixed
   */
  abstract public function get($id);

}