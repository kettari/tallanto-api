<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 06.01.2017
 * Time: 16:29
 */

namespace Tallanto\Api\Entity;


class ClassAggregator extends Aggregator {

  /**
   * @return \Tallanto\Api\Entity\ClassIterator
   */
  public function createIterator() {
    return new ClassIterator($this->items);
  }

}