<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 10.02.2017
 * Time: 19:38
 */

namespace Tallanto\Api\Provider\Database;

use PDO;

class ContactTempDatabaseProvider extends AbstractDatabaseProvider {

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
    
      FROM contact_temps ct
      
      WHERE ct.deleted = 0%s
      ORDER BY ct.last_name, ct.first_name
      
      %s', $select_clause, $where_clause, $limit_clause);
  }

  /**
   * Prepares SELECT SQL clause.
   *
   * @return string
   */
  protected function getSelectClause() {
    return 'ct.id AS id,
        ct.first_name,
        ct.last_name,
        ct.phone_mobile,
        ct.phone_work,
        ct.email1 AS email,
        DATE_FORMAT(ct.date_entered, "%Y-%m-%dT%H:%i:%sZ") AS "date_created",
        DATE_FORMAT(ct.date_modified, "%Y-%m-%dT%H:%i:%sZ") AS "date_updated",
        ct.filial AS branches';
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
        (ct.id = :query_exact';
      if (!$this->isQueryDisableLike()) {
        $where_clause .= ' OR
          ct.first_name LIKE :query_like OR 
          ct.last_name LIKE :query_like OR
          ct.phone_mobile LIKE :query_like OR
          ct.phone_work LIKE :query_like';
      }
      $where_clause .= ')';
    }
    if (!is_null($this->getIfModifiedSince())) {
      $where_clause .= ' AND ct.date_modified > :modified_since';
    }

    return $where_clause;
  }

  /**
   * Returns total number of records that fulfil the criteria.
   *
   * @return int
   * @throws \Doctrine\DBAL\DBALException
   */
  function totalCount() {
    $sql = $this->getMainSql('COUNT(DISTINCT ct.id)', $this->getWhereClause(),
      '');
    $stmt = $this->connection->executeQuery($sql, [
      'query_like'     => '%'.$this->query.'%',
      'query_exact'    => $this->query,
      'modified_since' => !is_null($this->if_modified_since) ? $this->if_modified_since->format('Y-m-d H:i:s') : 0,
    ], [PDO::PARAM_STR, PDO::PARAM_STR, PDO::PARAM_STR]);

    // Fetch column, it contains records count
    return $stmt->fetchColumn();
  }


}