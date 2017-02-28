<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 06.01.2017
 * Time: 16:29
 */

namespace Tallanto\Api\Aggregator;


use Tallanto\Api\Entity\Subject;

class SubjectAggregator extends AbstractEntityAggregator {

  /**
   * Add (create) entity to the storage. Copy of the object
   * is added to this aggregator's internal storage.
   *
   * @param mixed $entity
   * @return string
   * @throws \Exception
   */
  public function add($entity) {
    // Unset total records count for safety
    $this->total_count = NULL;
    // TODO: Implement add() method.
    throw new \Exception('SubjectAggregator::add() not implemented');
  }

  /**
   * Update entity in the storage.
   *
   * @param mixed $entity
   * @throws \Exception
   */
  public function update($entity) {
    // Unset total records count for safety
    $this->total_count = NULL;
    // TODO: Implement update() method.
    throw new \Exception('SubjectAggregator::update() not implemented');
  }

  /**
   * Creates Subject object.
   *
   * @param array $row
   * @return \Tallanto\Api\Entity\Subject
   */
  protected function buildObject(array $row) {
    return new Subject($row);
  }


}