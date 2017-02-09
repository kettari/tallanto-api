<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 06.01.2017
 * Time: 16:29
 */

namespace Tallanto\Api\Aggregator;


use PDO;
use Tallanto\Api\Entity\Contact;

class ContactAggregator extends DatabaseAggregator {

  /**
   * Searches for contacts (and fetches) in the database
   *
   * @param $query
   * @return $this
   */
  public function search($query) {
    // Set query and clear ID
    $this->setId(NULL);
    $this->setQuery($query);
    // Fetch data
    $this->fetch();

    return $this;
  }

  /**
   * Get entity by ID
   *
   * @param string $id
   * @return null|\Tallanto\Api\Entity\Contact
   */
  public function get($id) {
    // Set ID and clear query
    $this->setId($id);
    $this->setQuery(NULL);
    // Fetch data
    $this->fetch();
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
   * @inheritdoc
   */
  function fetch() {
    $sql = $this->getContactsSql(
      $this->getSelectClause(),
      $this->getWhereClause(),
      $this->getLimitClause($this->offset, $this->limit)
    );
    $stmt = $this->connection->getConnection()->executeQuery(
      $sql,
      [
        'contact_id' => $this->id,
        'query'      => '%' . $this->query . '%',
      ],
      [
        PDO::PARAM_STR,
        PDO::PARAM_STR,
      ]
    );

    // Fetch all results into associative array
    $dataset = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Clear items
    $this->clear();
    // Iterate rows and create Contact objects
    foreach ($dataset as $contact_row) {
      $contact = self::createContact($contact_row);
      $this->append($contact);
    }

    return $this;
  }

  /**
   * @inheritdoc
   */
  function totalCount() {
    $sql = $this->getContactsSql(
      'COUNT(DISTINCT c.id)',
      $this->getWhereClause(),
      ''
    );
    $stmt = $this->connection->getConnection()->executeQuery(
      $sql,
      [
        'contact_id' => $this->id,
        'query'      => '%' . $this->query . '%',
      ],
      [
        PDO::PARAM_STR,
        PDO::PARAM_STR,
      ]
    );

    // Fetch column
    $records_count = $stmt->fetchColumn();

    return $records_count;
  }


  /**
   * Prepare SELECT SQL clause
   *
   * @return string
   */
  protected function getSelectClause() {
    return
      'c.id AS contact_id,
        c.first_name,
        c.last_name,
        c.phone_home,
        c.phone_mobile,
        c.phone_work,
        c.phone_other,
        c.phone_fax,
        c.last_contact_date,
        DATE_FORMAT(c.date_entered, "%Y-%m-%dT%H:%i:%sZ") AS "date_entered",
        DATE_FORMAT(c.date_modified, "%Y-%m-%dT%H:%i:%sZ") AS "date_modified",
        c.assigned_user_id AS manager_id,
        cs.type_client_c';
  }

  /**
   * Prepare WHERE SQL clause
   *
   * @return string
   */
  protected function getWhereClause() {
    // Format WHERE clause of the SQL statement
    $where_clause = '';
    if (!empty($this->id)) {
      $where_clause .= ' AND c.id = :contact_id';
    }
    if (!empty($this->query)) {
      $where_clause .= ' AND 
        (
          c.first_name LIKE :query OR 
          c.last_name LIKE :query OR
          c.phone_home LIKE :query OR
          c.phone_mobile LIKE :query OR
          c.phone_work LIKE :query OR
          c.phone_other LIKE :query OR
          c.phone_fax LIKE :query
        )';
    }

    return $where_clause;
  }

  /**
   * Get LIMIT SQL clause
   *
   * @param integer $items_offset
   * @param integer $items_limit
   * @return string
   */
  protected function getLimitClause($items_offset, $items_limit) {
    return sprintf('LIMIT %d, %d', $items_offset, $items_limit);
  }

  /**
   * Retrieve data from the row and return Contact object
   *
   * @param array $row
   * @return \Tallanto\Api\Entity\Contact
   */
  public static function createContact(array $row) {
    return new Contact([
      'id'              => $row['contact_id'],
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
   * Get the SQL for Contacts.
   *
   * About prepared statements and LIMIT problem
   *
   * @see http://stackoverflow.com/a/20467350/7194257
   *
   * @param string $select_clause
   * @param string $where_clause
   * @param string $limit_clause
   * @return string
   */
  protected function getContactsSql($select_clause, $where_clause, $limit_clause) {
    return sprintf('
      SELECT
        %s
    
      FROM contacts c
      
      LEFT JOIN contacts_cstm cs ON cs.id_c = c.id
      
      WHERE c.deleted = 0%s
      ORDER BY c.last_name, c.first_name
      
      %s', $select_clause, $where_clause, $limit_clause);
    /*
     * e.email_addresses,
     * LEFT JOIN email_addr_bean_rel eb ON eb.bean_id = c.id AND eb.bean_module = "Contacts"
        AND eb.deleted = 0
      LEFT JOIN email_addresses e ON e.id = eb.email_address_id AND e.deleted = 0
     *
     */
  }

}