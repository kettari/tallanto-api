<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 06.01.2017
 * Time: 16:29
 */

namespace Tallanto\Api\Aggregator;

use Tallanto\Api\Entity\Contact;

class ContactAggregator extends AbstractAggregator implements AggregatorInterface {

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
   * @return bool TRUE if something were found, FALSE otherwise.
   */
  public function search($query) {
    // Set query and clear ID, then fetch data from the provider
    $result = $this->provider
      ->setQuery($query)
      ->fetch();
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
    // Iterate rows and create Contact objects
    foreach ($result as $row) {
      $contact = self::createContact($row);
      $this->append($contact);
    }
  }

  /**
   * Get entity by ID
   *
   * @param string $id
   * @return null|\Tallanto\Api\Entity\Contact
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
      /** @var Contact $contact */
      $contact = $iterator->current();
      if ($id == $contact->getId()) {
        return $contact;
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
  public function add($entity) {
    // Unset total records count for safety
    $this->total_count = NULL;
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
    // Unset total records count for safety
    $this->total_count = NULL;
    // TODO: Implement update() method.
    throw new \Exception('ContactAggregator::update() not implemented');
  }


  /**
   * Retrieve data from the row and return Contact object
   *
   * @param array $row
   * @return \Tallanto\Api\Entity\Contact
   */
  public static function createContact(array $row) {
    return new Contact([
      'id'              => $row['id'],
      'first_name'      => $row['first_name'],
      'last_name'       => $row['last_name'],
      'phone_home'      => $row['phone_home'],
      'phone_mobile'    => $row['phone_mobile'],
      'phone_work'      => $row['phone_work'],
      'phone_other'     => $row['phone_other'],
      'phone_fax'       => $row['phone_fax'],
      'type'            => $row['type_client_c'],
      'email_addresses' => NULL,
      'manager_id'      => $row['manager_id'],
      'date_created'    => $row['date_entered'],
      'date_updated'    => $row['date_modified'],
    ]);
  }

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