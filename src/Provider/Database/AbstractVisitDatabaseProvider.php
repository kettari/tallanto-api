<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 10.02.2017
 * Time: 19:38
 */

namespace Tallanto\Api\Provider\Database;


use PDO;

abstract class AbstractVisitDatabaseProvider extends AbstractDatabaseProvider
{

  /**
   * Returns total number of records that fulfil the criteria.
   *
   * @return int
   * @throws \Doctrine\DBAL\DBALException
   */
  function totalCount()
  {
    $sql = $this->getMainSql(
      'COUNT(DISTINCT mcc.id)',
      $this->getWhereClause(),
      ''
    );
    $stmt = $this->connection->executeQuery(
      $sql,
      [
        'query_like'     => '%'.$this->query.'%',
        'query_exact'    => $this->query,
        'modified_since' => !is_null(
          $this->if_modified_since
        ) ? $this->if_modified_since->format('Y-m-d H:i:s') : 0,
      ],
      [PDO::PARAM_STR, PDO::PARAM_STR, PDO::PARAM_STR]
    );

    // Fetch column, it contains records count
    return $stmt->fetchColumn();
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
  protected function getMainSql($select_clause, $where_clause, $limit_clause)
  {
    return sprintf(
      '
      SELECT
        %s
    
      FROM most_class_contacts_c mcc
      
      INNER JOIN most_class mc ON mc.id = mcc.most_class_id AND mc.deleted = 0
      INNER JOIN contacts c ON c.id = mcc.contact_id AND c.deleted = 0
      INNER JOIN most_abonements ma ON ma.id = mcc.most_class_abonements AND ma.deleted = 0
      
      WHERE mcc.deleted = 0%s
      
      %s',
      $select_clause,
      $where_clause,
      $limit_clause
    );
  }

  /**
   * Prepares SELECT SQL clause.
   *
   * @return string
   */
  protected function getSelectClause()
  {
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


}