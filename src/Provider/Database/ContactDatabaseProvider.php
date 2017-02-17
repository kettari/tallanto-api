<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 10.02.2017
 * Time: 19:38
 */

namespace Tallanto\Api\Provider\Database;


use PDO;

class ContactDatabaseProvider extends AbstractDatabaseProvider {

  /**
   * Gets main SQL.
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
  protected function getMainSql($select_clause, $where_clause, $limit_clause) {
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
   * Prepares SELECT SQL clause.
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
        DATE_FORMAT(c.date_entered, "%Y-%m-%dT%H:%i:%sZ") AS "date_created",
        DATE_FORMAT(c.date_modified, "%Y-%m-%dT%H:%i:%sZ") AS "date_updated",
        c.assigned_user_id AS manager_id,
        cs.type_client_c AS `type`';
  }

  /**
   * Prepares WHERE SQL clause
   *
   * @return string
   */
  protected function getWhereClause() {
    // Format WHERE clause of the SQL statement
    $where_clause = '';
    if (!empty($this->query)) {
      $where_clause .= ' AND 
        (c.id = :query_exact';
      if (!$this->isQueryDisableLike()) {
        $where_clause .= ' OR
          c.first_name LIKE :query_like OR 
          c.last_name LIKE :query_like OR
          c.phone_home LIKE :query_like OR
          c.phone_mobile LIKE :query_like OR
          c.phone_work LIKE :query_like OR
          c.phone_other LIKE :query_like OR
          c.phone_fax LIKE :query_like';
      }
      $where_clause .= ')';
    }

    return $where_clause;
  }

  /**
   * Returns total number of records that fulfil the criteria.
   *
   * @return int
   */
  function totalCount() {
    $sql = $this->getMainSql(
      'COUNT(DISTINCT c.id)',
      $this->getWhereClause(),
      ''
    );
    $stmt = $this->connection->executeQuery(
      $sql,
      [
        'query_like'  => '%' . $this->query . '%',
        'query_exact' => $this->query,
      ],
      [PDO::PARAM_STR]
    );

    // Fetch column, it contains records count
    return $stmt->fetchColumn();
  }


}