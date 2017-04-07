<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 06.01.2017
 * Time: 16:29
 */

namespace Tallanto\Api\Aggregator;



use Tallanto\Api\Entity\Branch;

class BranchAggregator extends AbstractEntityAggregator {

  /**
   * Parse array received from the provider and create objects.
   *
   * @param array $result
   */
  protected function parseResult($result) {
    // Clear items
    $this->clear();
    // Iterate rows and create objects
    foreach ($result as $row) {
      /** @var Branch $branch */
      $branch = $this->buildObject($row);
      if (!empty($branch->getName())) {
        $this->append($branch);
      }
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
    throw new \Exception('AudienceAggregator::add() not implemented');
  }

  /**
   * Update entity in the storage.
   *
   * @param mixed $entity
   * @throws \Exception
   */
  public function update($entity) {
    throw new \Exception('AudienceAggregator::update() not implemented');
  }

  /**
   * Creates Audience object.
   *
   * @param array $row
   * @return \Tallanto\Api\Entity\Branch
   */
  protected function buildObject(array $row) {
    return new Branch($row);
  }


}