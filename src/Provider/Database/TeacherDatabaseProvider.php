<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 10.02.2017
 * Time: 19:38
 */

namespace Tallanto\Api\Provider\Database;


use PDO;


class TeacherDatabaseProvider extends AbstractDatabaseProvider {

  /**
   * Fetches (loads) data from the upstream using page number, page size and
   * possible ID and query values.
   *
   * Returns array if everything is OK.
   *
   * @return array
   */
  function fetch() {
    $users = parent::fetch();

    // Expand Users with emails
      $email_provider = new UserEmailDatabaseProvider($this->connection);
      foreach ($users as $key => $item) {
        $email_provider->setQuery($item['id']);
        $users[$key]['emails'] = $email_provider->fetch();
      }

    return $users;
  }

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
    
      FROM most_class mc
      
      LEFT JOIN most_class_employees_c mce ON mce.most_class_id = mc.id AND mce.deleted = 0
      LEFT JOIN users u ON u.id = mce.employee_id
      LEFT JOIN stakes s ON s.id = mce.stake_id
      
      WHERE mc.deleted = 0%s
      ORDER BY u.last_name, u.first_name
      
      %s', $select_clause, $where_clause, $limit_clause);
  }

  /**
   * Prepares SELECT SQL clause.
   *
   * @return string
   */
  protected function getSelectClause() {
    return 'u.id AS id,
        u.user_name,
        u.first_name,
        u.last_name,
        u.phone_mobile,
        u.phone_work,
        u.filial AS branches,
        DATE_FORMAT(u.date_entered, "%Y-%m-%dT%H:%i:%sZ") AS "date_created",
        DATE_FORMAT(u.date_modified, "%Y-%m-%dT%H:%i:%sZ") AS "date_updated",
        u.status AS user_status,
        u.employee_status AS employee_status,
        u.balance_money AS balance,
        mce.profit,
        s.id AS stake_id,
        s.name AS stake_name';
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
      $where_clause .= ' AND mc.id = :query_exact';
    }

    return $where_clause;
  }

  /**
   * Returns total number of records that fulfil the criteria.
   *
   * @return int
   */
  function totalCount() {
    $sql = $this->getMainSql('COUNT(DISTINCT u.id)', $this->getWhereClause(),
      '');
    $stmt = $this->connection->executeQuery($sql, [
      'query_like'  => '%'.$this->query.'%',
      'query_exact' => $this->query,
    ], [PDO::PARAM_STR]);

    // Fetch column, it contains records count
    return $stmt->fetchColumn();
  }

}