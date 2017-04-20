<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 10.02.2017
 * Time: 19:38
 */

namespace Tallanto\Api\Provider\Database;


use Tallanto\Api\ExpandableInterface;
use Tallanto\Api\ExpandableTrait;

class UserPayrollDatabaseProvider extends AbstractDatabaseProvider implements ExpandableInterface
{

  use ExpandableTrait;

  /**
   * Fetches (loads) data from the upstream using page number, page size and
   * possible ID and query values.
   *
   * Returns array if everything is OK.
   *
   * @return array
   */
  function fetch()
  {
    $payrolls = parent::fetch();

    // Expand Users with emails
    if ($this->isExpand()) {

      // Local copies
      $local_copy = $this->getLocalCopy();

      $class_provider = new ClassDatabaseProvider($this->connection);
      $class_provider->setExpand(true)
        ->setQueryDisableLike(true);

      foreach ($payrolls as $key => $item) {
        if (is_null($local_copy->getCache('classes', $item['class_id'])) &&
          !is_null($item['class_id'])
        ) {
          $class_provider->setQuery($item['class_id']);
          $classes = $class_provider->fetch();
          $local_copy->setCache(
            'classes',
            $item['class_id'],
            reset($classes)
          );
        }
        $payrolls[$key]['class'] = $local_copy->getCache(
          'classes',
          $item['class_id']
        );
      }
    }

    return $payrolls;
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
    
      FROM payrolls p
      
      WHERE p.deleted = 0%s
      ORDER BY p.date_payment DESC
      
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
    return 'p.id AS id,
        p.name,
        DATE_FORMAT(p.date_entered, "%Y-%m-%dT%H:%i:%sZ") AS "date_created",
        DATE_FORMAT(p.date_modified, "%Y-%m-%dT%H:%i:%sZ") AS "date_updated",
        p.description,
        p.cost,
        DATE_FORMAT(p.date_payment, "%Y-%m-%d"),
        p.employee_id,
        p.audience,
        p.filial AS "branches",
        p.most_class_id AS "class_id"';
  }

  /**
   * Prepares WHERE SQL clause
   *
   * @return string
   */
  protected function getWhereClause()
  {
    // Format WHERE clause of the SQL statement
    $where_clause = '';
    if (!empty($this->query)) {
      $where_clause .= ' AND 
        (p.id = :query_exact OR
        p.employee_id = :parameter';

      // Select date format to compare date
      if (preg_match('/^\d{4}-\d{2}-\d{2}$/i', $this->getQuery())) {
        $date_format = '%Y-%m-%d';
      } elseif (preg_match(
        '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}Z$/i',
        $this->getQuery()
      )) {
        $date_format = '%Y-%m-%dT%H:%iZ';
      } elseif (preg_match(
        '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}Z$/i',
        $this->getQuery()
      )) {
        $date_format = '%Y-%m-%dT%H:%i:%sZ';
      } else {
        $date_format = false;
      }

      // If query is a date, then look in date fields only
      if (false !== $date_format) {

        $where_clause .= sprintf(
          ' OR DATE_FORMAT(p.date_payment, "%s") = :query_exact',
          $date_format
        );

      } else {

        if (!$this->isQueryDisableLike()) {
          $where_clause .= ' OR
          p.name LIKE :query_like OR 
          p.description LIKE :query_like';
        }

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
  function totalCount()
  {
    $sql = $this->getMainSql(
      'COUNT(DISTINCT p.id)',
      $this->getWhereClause(),
      ''
    );
    $stmt = $this->connection->executeQuery(
      $sql,
      [
        'parameter'   => $this->parameter,
        'query_like'  => '%'.$this->query.'%',
        'query_exact' => $this->query,
      ]
    );

    // Fetch column, it contains records count
    return $stmt->fetchColumn();
  }

}