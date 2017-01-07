<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 06.01.2017
 * Time: 16:30
 */

namespace Tallanto\Api\Entity;




abstract class Aggregator implements AggregateInterface {

  /**
   * @var array
   */
  protected $items = [];

  /**
   * @param mixed $entity
   * @return Aggregator
   */
  public function add($entity) {
    $this->items[] = $entity;

    return $this;
  }

}