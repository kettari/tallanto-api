<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 10.02.2017
 * Time: 19:38
 */

namespace Tallanto\Api\Provider\Database;


use PDO;

class VisitDatabaseProvider extends AbstractDatabaseProvider {

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
    
      FROM most_class_contacts_c mcc
      
      WHERE mcc.deleted = 0%s
      
      %s', $select_clause, $where_clause, $limit_clause);
  }

  /**
   * Prepares SELECT SQL clause.
   *
   * @return string
   */
  protected function getSelectClause() {
    return 'mcc.id AS id,
        mcc.most_class_id AS class_id,
        mcc.contact_id AS contact_id,
        mcc.most_class_abonements AS ticket_id,
        mcc.most_class_contacts_status AS status,
        mcc.most_class_user_id AS manager_id,
        DATE_FORMAT(mcc.date_entry, "%Y-%m-%dT%H:%i:%sZ") AS "date_created",
        DATE_FORMAT(mcc.date_modified, "%Y-%m-%dT%H:%i:%sZ") AS "date_updated",
        mcc.write_yourself AS self_service
      ';
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
        (mcc.most_class_id = :query_exact OR
         mcc.contact_id = :query_exact)';
    }

    return $where_clause;
  }

  /**
   * Returns total number of records that fulfil the criteria.
   *
   * @return int
   */
  function totalCount() {
    $sql = $this->getMainSql('COUNT(DISTINCT mcc.id)', $this->getWhereClause(),
      '');
    $stmt = $this->connection->executeQuery($sql, [
      'query_like'  => '%'.$this->query.'%',
      'query_exact' => $this->query,
    ], [PDO::PARAM_STR]);

    // Fetch column, it contains records count
    return $stmt->fetchColumn();
  }


}