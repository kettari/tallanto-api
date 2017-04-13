<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 10.02.2017
 * Time: 19:38
 */

namespace Tallanto\Api\Provider\Database;


use PDO;


class BranchDatabaseProvider extends AbstractDatabaseProvider {

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
    
      FROM translated_lists_for_report tl
      
      WHERE tl.list_name="filial_list" AND tl.value IS NOT NULL AND tl.value <> \'\'%s
      ORDER BY tl.translate
      
      %s', $select_clause, $where_clause, $limit_clause);
  }

  /**
   * Prepares SELECT SQL clause.
   *
   * @return string
   */
  protected function getSelectClause() {
    return 'tl.value AS `name`,
      tl.translate AS `title`';
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
        (tl.value = :query_exact';
      if (!$this->isQueryDisableLike()) {
        $where_clause .= ' OR
          tl.translate LIKE :query_like';
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
    $sql = $this->getMainSql('COUNT(DISTINCT tl.value)', $this->getWhereClause(),
      '');
    $stmt = $this->connection->executeQuery($sql, [
      'query_like'  => '%'.$this->query.'%',
      'query_exact' => $this->query,
    ], [PDO::PARAM_STR]);

    // Fetch column, it contains records count
    return $stmt->fetchColumn();
  }

}