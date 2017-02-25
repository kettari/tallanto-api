<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 10.02.2017
 * Time: 19:38
 */

namespace Tallanto\Api\Provider\Database;


abstract class AbstractEmailDatabaseProvider extends AbstractDatabaseProvider {

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
    
      FROM email_addr_bean_rel eb
      INNER JOIN email_addresses e ON e.id = eb.email_address_id AND e.deleted = 0
      
      WHERE eb.bean_module = "%s" AND eb.deleted = 0%s
      
      %s', $select_clause, $this->getEmailBeanModule(), $where_clause, $limit_clause);
  }

  /**
   * @return string
   */
  abstract protected function getEmailBeanModule();

  /**
   * Prepares SELECT SQL clause.
   *
   * @return string
   */
  protected function getSelectClause() {
    return 'e.email_address AS address,
        e.invalid_email AS invalid,
        e.opt_out AS opt_out,
        DATE_FORMAT(e.date_created, "%Y-%m-%dT%H:%i:%sZ") AS "date_created",
        DATE_FORMAT(e.date_modified, "%Y-%m-%dT%H:%i:%sZ") AS "date_updated",
        eb.primary_address AS main';
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
      $where_clause .= ' AND eb.bean_id = :query_exact';
    }

    return $where_clause;
  }

  /**
   * Returns total number of records that fulfil the criteria.
   *
   * @return int
   */
  function totalCount() {
    return NULL;
  }


}