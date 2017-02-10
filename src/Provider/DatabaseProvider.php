<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 10.02.2017
 * Time: 19:38
 */

namespace Tallanto\Api\Provider;


use PDO;
use Tallanto\Api\Provider\Database\DatabaseClient;

class DatabaseProvider extends AbstractProvider implements ProviderInterface {

  /**
   * @var DatabaseClient
   */
  protected $connection;

  /**
   * DatabaseProvider constructor.
   *
   * @param \Tallanto\Api\Provider\Database\DatabaseClient $connection
   */
  public function __construct(DatabaseClient $connection) {
    $this->connection = $connection;
  }

  /**
   * Fetches (loads) data from the upstream using page number, page size and
   * possible ID and query values.
   *
   * Returns array if everything is OK.
   *
   * @return array
   */
  function fetch() {
    $offset = ($this->getPageNumber() - 1) * $this->getPageSize();
    $sql = $this->getContactsSql(
      $this->getSelectClause(),
      $this->getWhereClause(),
      $this->getLimitClause($offset, $this->getPageSize())
    );
    $stmt = $this->connection->getConnection()->executeQuery(
      $sql,
      ['query' => '%' . $this->query . '%'],
      [
        PDO::PARAM_STR,
      ]
    );

    // Fetch all results into associative array
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

  /**
   * Prepare SELECT SQL clause
   *
   * @return string
   */
  protected function getSelectClause() {
    return
      'c.id AS id,
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
    if (!empty($this->query)) {
      $where_clause .= ' AND 
        (
          c.id = :query OR
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
   * Returns total number of records that fulfil the criteria.
   *
   * @return int
   */
  function totalCount() {
    $sql = $this->getContactsSql(
      'COUNT(DISTINCT c.id)',
      $this->getWhereClause(),
      ''
    );
    $stmt = $this->connection->getConnection()->executeQuery(
      $sql,
      ['query' => '%' . $this->query . '%'],
      [PDO::PARAM_STR]
    );

    // Fetch column, it contains records count
    return $stmt->fetchColumn();
  }


}