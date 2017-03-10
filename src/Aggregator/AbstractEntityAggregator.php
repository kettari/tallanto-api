<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 10.02.2017
 * Time: 23:13
 */

namespace Tallanto\Api\Aggregator;


use Tallanto\Api\Entity\AbstractIdentifiableEntity;

abstract class AbstractEntityAggregator extends AbstractAggregator implements AggregatorInterface {

  /**
   * Total count of records available from the provider.
   *
   * @var integer
   */
  protected $total_count;

  /**
   * Search entities for the substring. Searches in names, phones and emails.
   * Result is placed to the internal storage and is iteratable.
   *
   * @param string $query
   * @param bool $fetch_all
   * @return bool TRUE if something were found, FALSE otherwise.
   */
  public function search($query, $fetch_all = FALSE) {
    // Set query then fetch data from the provider
    $this->provider->setQuery($query);
    if ($fetch_all) {
      $result = $this->provider->fetchAll();
    } else {
      $result = $this->provider->fetch();
    }

    // Parse result and fill the aggregator with objects
    //dump($result);
    $this->parseResult($result);
    // Store total records count
    $this->total_count = $this->provider->totalCount();

    return $this->count() > 0;
  }

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
      $object = $this->buildObject($row);
      $this->append($object);
    }
  }

  /**
   * Build object using provided data.
   *
   * @param array $data
   * @return mixed
   */
  abstract protected function buildObject(array $data);

  /**
   * Get entity by ID
   *
   * @param string $id
   * @return null|\Tallanto\Api\Entity\AbstractEntity
   */
  public function get($id) {
    // Search with given ID
    if (!$this->search($id)) {
      // Unset total records count for safety
      $this->total_count = NULL;

      return NULL;
    }
    // Unset total records count for safety
    $this->total_count = NULL;
    // Find the object by ID
    $iterator = $this->getIterator();
    while ($iterator->valid()) {
      /** @var AbstractIdentifiableEntity $entity */
      $entity = $iterator->current();
      if ($id == $entity->getId()) {
        return $entity;
      }
      $iterator->next();
    }

    return NULL;
  }

  /**
   * Add (create) entity to the storage. Copy of the object
   * is added to this aggregator's internal storage.
   *
   * @param mixed $entity
   * @return string
   * @throws \Exception
   */
  abstract public function add($entity);

  /**
   * Update entity in the storage.
   *
   * @param mixed $entity
   * @throws \Exception
   */
  abstract public function update($entity);

  /**
   * Returns total count of records available from the provider
   * that fulfills conditions.
   *
   * @return int
   * @throws \Exception
   */
  public function totalCount() {
    if (is_null($this->total_count)) {
      throw new \Exception('Unable to tell total records count prior to search() method.');
    }

    return $this->total_count;
  }


}