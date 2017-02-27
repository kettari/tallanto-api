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
   * Parse array received from the provider and create objects.
   *
   * @param array $result
   */
  protected function parseResult($result) {
    // Clear items
    $this->clear();
    // Iterate rows and create Subject objects
    foreach ($result as $row) {
      $contact = self::buildSubject($row);
      $this->append($contact);
    }
  }

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
   * Retrieves data from the row and returns Contact object.
   *
   * @param array $row
   * @return \Tallanto\Api\Entity\Subject
   */
  public static function buildSubject(array $row) {
    return new Subject($row);
  }

}