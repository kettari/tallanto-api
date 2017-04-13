<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 10.02.2017
 * Time: 19:38
 */

namespace Tallanto\Api\Provider\Database;


use PDO;


class UserClassDatabaseProvider extends ClassDatabaseProvider
{
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
  protected function getMainSql($select_clause, $where_clause, $limit_clause)
  {
    // Workaround to get proper count()
    if (false === strpos($select_clause, 'COUNT(DISTINCT mc.id)')) {
      $group_by = ' GROUP BY mc.id ';
    } else {
      $group_by = '';
    }

    return sprintf(
      '
      SELECT
        %s
    
      FROM most_class mc
      INNER JOIN most_class_employees_c mce ON mce.deleted = 0 AND mce.most_class_id = mc.id AND mce.employee_id = :parameter
      
      WHERE mc.deleted = 0%s
      %s
      
      %s',
      $select_clause,
      $where_clause,
      $group_by,
      $limit_clause
    );
  }

  /**
   * Returns total number of records that fulfil the criteria.
   *
   * @return int
   */
  function totalCount()
  {
    $sql = $this->getMainSql(
      'COUNT(DISTINCT mc.id)',
      $this->getWhereClause(),
      ''
    );
    $stmt = $this->connection->executeQuery(
      $sql,
      [
        'parameter'      => $this->parameter,
        'query_like'     => '%'.$this->query.'%',
        'query_exact'    => $this->query,
        'modified_since' => !is_null(
          $this->if_modified_since
        ) ? $this->if_modified_since->format('Y-m-d H:i:s') : 0,
      ],
      [
        PDO::PARAM_STR,
        PDO::PARAM_STR,
        PDO::PARAM_STR,
        PDO::PARAM_STR,
      ]
    );

    // Fetch column, it contains records count
    return $stmt->fetchColumn();
  }


}