<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 06.01.2017
 * Time: 16:50
 */

namespace Tallanto\Api\Entity;




interface AggregateInterface {

  /**
   * Each aggregator is able to create iterator to follow it's collection
   *
   * @return IteratorInterface
   */
  public function createIterator();

  /**
   * @param mixed $entity
   * @return mixed
   */
  public function add($entity);

}