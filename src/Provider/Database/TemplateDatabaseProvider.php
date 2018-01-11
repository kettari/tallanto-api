<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 10.02.2017
 * Time: 19:38
 */

namespace Tallanto\Api\Provider\Database;


use PDO;


class TemplateDatabaseProvider extends AbstractDatabaseProvider {

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
    
      FROM most_template_abonements mta
      
      WHERE mta.deleted = 0%s
      ORDER BY mta.name
      
      %s', $select_clause, $where_clause, $limit_clause);
  }

  /**
   * Prepares SELECT SQL clause.
   *
   * @return string
   */
  protected function getSelectClause() {
    return 'mta.id AS id,
        DATE_FORMAT(mta.date_entered, "%Y-%m-%dT%H:%i:%sZ") AS "date_created",
        DATE_FORMAT(mta.date_modified, "%Y-%m-%dT%H:%i:%sZ") AS "date_updated",
        mta.name,
        mta.cost,
        mta.class_cost_for_inclusive AS single_visit_cost,
        mta.duration,
        mta.num_visit,
        mta.active,
        mta.filial AS branches';
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
      $where_clause .= ' AND (mta.id = :query_exact';
      if (!$this->isQueryDisableLike()) {
        $where_clause .= ' OR
          mta.name LIKE :query_like';
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
    $sql = $this->getMainSql('COUNT(DISTINCT mta.id)', $this->getWhereClause(),
      '');
    $stmt = $this->connection->executeQuery($sql, [
        'query_like'  => '%'.$this->query.'%',
        'query_exact' => $this->query,
      ], [PDO::PARAM_STR]);

    // Fetch column, it contains records count
    return $stmt->fetchColumn();
  }


}