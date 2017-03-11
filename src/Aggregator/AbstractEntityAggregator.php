<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 10.02.2017
 * Time: 23:13
 */

namespace Tallanto\Api\Aggregator;


use Tallanto\Api\Entity\AbstractIdentifiableEntity;
use Tallanto\Api\Exception\MultipleItemsException;

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
   * Loads one page.
   *
   * @param string $query
   * @return bool Returns TRUE if at least one record was found
   */
  public function search($query) {
    return $this->searchEx($query);
  }

  /**
   * Search entities for the substring. Searches in names, phones and emails.
   * Result is placed to the internal storage and is iteratable. Loads all
   * records taking into account pagination.
   *
   * @param string $query
   * @param callable|NULL $callback
   * @return bool Returns TRUE if at least one record was found
   */
  public function searchEx($query, callable $callback = NULL) {
    // Set query then fetch data from the provider
    $this->provider->setQuery($query);
    if (is_null($callback)) {
      $result = $this->provider->fetch();
    }
    else {
      $result = $this->provider->fetchAll($callback);
    }

    // Parse result and fill the aggregator with objects
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
   * @throws \Tallanto\Api\Exception\MultipleItemsException
   */
  public function get($id) {
    // Search with given ID
    if (!$this->search($id)) {
      $this->total_count = 0;

      return NULL;
    }

    // If more than one item was found, throw an exception
    if ($this->count() > 1) {
      throw new MultipleItemsException(sprintf('Multiple items found for ID "%s"',
        $id));
    }

    // Extract single item
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
   */
  public function totalCount() {
    return $this->total_count;
  }


}