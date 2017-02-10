<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 10.02.2017
 * Time: 16:27
 */

namespace Tallanto\Api\Aggregator;


interface AggregatorInterface {

  /**
   * Search entities for the substring. Searches in names, phones and emails.
   * Result is placed to the internal storage and is iteratable.
   *
   * @param string $query
   * @return bool TRUE if something were found, FALSE otherwise.
   */
  public function search($query);

  /**
   * Get the entity from the Tallanto using ID
   *
   * @param string $id Entity ID
   * @return bool TRUE if something were found, FALSE otherwise.
   */
  public function get($id);

  /**
   * Add (create) entity to the storage. Copy of the object
   * is added to this aggregator's internal storage.
   *
   * @param mixed $entity
   */
  public function add($entity);

  /**
   * Update entity in the storage.
   *
   * @param mixed $entity
   */
  public function update($entity);

  /**
   * Returns total count of records available from the provider
   * that fulfills conditions.
   *
   * @return integer
   */
  public function totalCount();

}