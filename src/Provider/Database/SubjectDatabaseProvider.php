<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 10.02.2017
 * Time: 19:38
 */

namespace Tallanto\Api\Provider\Database;


use PDO;


class SubjectDatabaseProvider extends AbstractDatabaseProvider {

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
    
      FROM most_subject ms
      
      WHERE ms.deleted = 0%s
      
      %s', $select_clause, $where_clause, $limit_clause);
  }

  /**
   * Prepares SELECT SQL clause.
   *
   * @return string
   */
  protected function getSelectClause() {
    return 'ms.id AS id,
        DATE_FORMAT(ms.date_entered, "%Y-%m-%dT%H:%i:%sZ") AS "date_created",
        DATE_FORMAT(ms.date_modified, "%Y-%m-%dT%H:%i:%sZ") AS "date_updated",
        ms.name,
        DATE_FORMAT(ms.date_start, "%Y-%m-%dT%H:%i:%sZ") AS "date_start",
        DATE_FORMAT(ms.date_finish, "%Y-%m-%dT%H:%i:%sZ") AS "date_finish",
        ms.description,
        ms.status,
        ms.most_class_calendar_hidden AS calendar_hidden,
        ms.default_stake_id,
        ms.filial AS branches';
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
        (ms.id = :query_exact';
      if (!$this->isQueryDisableLike()) {
        $where_clause .= ' OR
          ms.name LIKE :query_like';
      }
      $where_clause .= ')';
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
    $sql = $this->getMainSql('COUNT(DISTINCT ms.id)', $this->getWhereClause(),
      '');
    $stmt = $this->connection->executeQuery($sql, [
      'query_like'  => '%'.$this->query.'%',
      'query_exact' => $this->query,
    ], [PDO::PARAM_STR]);

    // Fetch column, it contains records count
    return $stmt->fetchColumn();
  }


}