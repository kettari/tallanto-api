<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 10.02.2017
 * Time: 19:38
 */

namespace Tallanto\Api\Provider\Database;


use PDO;

class TicketDatabaseProvider extends AbstractDatabaseProvider {

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
    
      FROM most_abonements a
      
      LEFT JOIN most_template_abonements ta ON ta.id = a.template_id
      
      WHERE a.deleted = 0%s
      ORDER BY a.name
      
      %s', $select_clause, $where_clause, $limit_clause);
  }

  /**
   * Prepares SELECT SQL clause.
   *
   * @return string
   */
  protected function getSelectClause() {
    return
      'a.id AS id,
        DATE_FORMAT(a.date_entered, "%Y-%m-%dT%H:%i:%sZ") AS "date_created",
        DATE_FORMAT(a.date_modified, "%Y-%m-%dT%H:%i:%sZ") AS "date_updated",
        a.name,
        DATE_FORMAT(a.start_date, "%Y-%m-%dT%H:%i:%sZ") AS "start_date",
        DATE_FORMAT(a.finish_date, "%Y-%m-%dT%H:%i:%sZ") AS "finish_date",
        ta.name AS template_name,
        a.template_id,
        a.contact_id AS owner_id,
        a.cost,
        a.cost_standard,
        a.duration,
        a.num_visit,
        a.num_visit_left,
        a.manual_closed';
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
        (a.id = :query_exact OR
          a.contact_id = :query_exact OR
          a.template_id = :query_exact';
      if (!$this->isQueryDisableLike()) {
        $where_clause .= ' OR
          a.name LIKE :query_like';
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
      'COUNT(DISTINCT a.id)',
      $this->getWhereClause(),
      ''
    );
    $stmt = $this->connection->getConnection()->executeQuery(
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