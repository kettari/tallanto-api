<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 06.01.2017
 * Time: 16:29
 */

namespace Tallanto\Api\Aggregator;


use Tallanto\Api\Entity\Contact;

class ContactAggregator extends AbstractEntityAggregator {

   /**
   * Add (create) entity to the storage. Copy of the object
   * is added to this aggregator's internal storage.
   *
   * @param mixed $entity
   * @return string
   * @throws \Exception
   */
  public function add($entity) {
    // TODO: Implement add() method.
    throw new \Exception('ContactAggregator::add() not implemented');
  }

  /**
   * Update entity in the storage.
   *
   * @param mixed $entity
   * @throws \Exception
   */
  public function update($entity) {
    // TODO: Implement update() method.
    throw new \Exception('ContactAggregator::update() not implemented');
  }

  /**
   * Creates Contact object.
   *
   * @param array $row
   * @return \Tallanto\Api\Entity\Contact
   */
  protected function buildObject(array $row) {
    return new Contact($row);
  }

}