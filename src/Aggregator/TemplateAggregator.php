<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 06.01.2017
 * Time: 16:29
 */

namespace Tallanto\Api\Aggregator;

use Tallanto\Api\Entity\Template;

class TemplateAggregator extends AbstractEntityAggregator {

  /**
   * Add (create) entity to the storage. Copy of the object
   * is added to this aggregator's internal storage.
   *
   * @param mixed $entity
   * @return string
   * @throws \Exception
   */
  public function add($entity) {
    throw new \Exception('TemplateAggregator::add() not implemented');
  }

  /**
   * Update entity in the storage.
   *
   * @param mixed $entity
   * @throws \Exception
   */
  public function update($entity) {
    throw new \Exception('TemplateAggregator::update() not implemented');
  }

  /**
   * Creates Template object.
   *
   * @param array $row
   * @return \Tallanto\Api\Entity\Template
   */
  protected function buildObject(array $row) {
    return new Template($row);
  }

}