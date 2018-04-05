<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 10.02.2017
 * Time: 19:38
 */

namespace Tallanto\Api\Provider\Database;


use PDO;
use Tallanto\Api\ExpandableInterface;
use Tallanto\Api\ExpandableTrait;

class ClassDatabaseProvider extends AbstractDatabaseProvider implements ExpandableInterface
{

  use ExpandableTrait;

  /**
   * Fetches (loads) data from the upstream using page number, page size and
   * possible ID and query values.
   *
   * Returns array if everything is OK.
   *
   * @return array
   * @throws \Doctrine\DBAL\DBALException
   */
  function fetch()
  {
    $classes = parent::fetch();

    // Expand Classes
    if ($this->isExpand()) {
      // Expand Subject
      $subject_provider = new SubjectDatabaseProvider($this->connection);
      $subject_provider->setQueryDisableLike(true);
      // Expand Teachers
      $teacher_provider = new TeacherDatabaseProvider($this->connection);
      $teacher_provider->setQueryDisableLike(true);

      // Local copies
      $local_copy = $this->getLocalCopy();

      foreach ($classes as $key_class => $class) {
        // Subject
        if (is_null(
            $local_copy->getCache('subjects', $class['subject_id'])
          ) && !is_null($class['subject_id'])
        ) {
          $subject_provider->setQuery($class['subject_id']);
          $subjects = $subject_provider->fetch();
          /** @var \Tallanto\Api\Entity\Subject $subject_item */
          $subject_item = reset($subjects);
          $local_copy->setCache(
            'subjects',
            $class['subject_id'],
            $subject_item
          );
        }
        $classes[$key_class]['subject'] = $local_copy->getCache(
          'subjects',
          $class['subject_id']
        );
        // Teacher
        if (is_null($local_copy->getCache('teachers', $class['teachers_hash'])) &&
          !is_null($class['teachers_hash'])
        ) {
          $teacher_provider->setQuery($class['id']);
          $local_copy->setCache(
            'teachers',
            $class['teachers_hash'],
            $teacher_provider->fetch()
          );
        }
        $classes[$key_class]['teachers'] = $local_copy->getCache(
          'teachers',
          $class['teachers_hash']
        );
      }
    }

    //dump($classes);
    return $classes;
  }

  /**
   * Returns total number of records that fulfil the criteria.
   *
   * @return int
   * @throws \Doctrine\DBAL\DBALException
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
      LEFT JOIN most_class_employees_c mce ON mce.deleted = 0 AND mce.most_class_id = mc.id
      
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
        (mc.id = :query_exact';

      $date_format = false;
      $range = false;
      $range_values = [];

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
      } elseif (preg_match('/^\d{4}-\d{2}$/i', $this->getQuery())) {
        $date_format = '%Y-%m';
      } elseif (preg_match(
        '/^(\d{4}-\d{2})\|(\d{4}-\d{2})$/i',
        $this->getQuery(),
        $matches
      )) {
        $date_format = '%Y-%m';
        $range = true;
        $range_values['begin'] = $matches[1] ?? '';
        $range_values['end'] = $matches[2] ?? '';
      } elseif (preg_match(
        '/^(\d{4}-\d{2}-\d{2})\|(\d{4}-\d{2}-\d{2})$/i',
        $this->getQuery(),
        $matches
      )) {
        $date_format = '%Y-%m-%d';
        $range = true;
        $range_values['begin'] = $matches[1] ?? '';
        $range_values['end'] = $matches[2] ?? '';
      }

      // If query is a date, then look in date fields only
      if (false !== $date_format) {

        if (!$range) {
          $where_clause .= sprintf(
            ' OR DATE_FORMAT(mc.date_start, "%s") = :query_exact',
            $date_format
          );
        } else {
          $where_clause .= sprintf(
            ' OR DATE_FORMAT(mc.date_start, "%s") BETWEEN "%s" AND "%s"',
            $date_format,
            $range_values['begin'],
            $range_values['end']
          );
        }

      } else {

        if (!$this->isQueryDisableLike()) {
          $where_clause .= sprintf(
            ' OR mc.name LIKE :query_like',
            $date_format
          );
        }

      }
      $where_clause .= ')';
    }
    if (!is_null($this->getIfModifiedSince())) {
      $where_clause .= ' AND mc.date_modified > :modified_since';
    }

    return $where_clause;
  }

  /**
   * Prepares SELECT SQL clause.
   *
   * @return string
   */
  protected function getSelectClause()
  {
    return 'mc.id AS id,
        DATE_FORMAT(mc.date_entered, "%Y-%m-%dT%H:%i:%sZ") AS "date_created",
        DATE_FORMAT(mc.date_modified, "%Y-%m-%dT%H:%i:%sZ") AS "date_updated",
        mc.name,
        mc.subject_id,
        DATE_FORMAT(mc.date_start, "%Y-%m-%dT%H:%i:%sZ") AS "date_start",
        DATE_FORMAT(mc.date_finish, "%Y-%m-%dT%H:%i:%sZ") AS "date_finish",
        mc.status,
        mc.cost,
        mc.profit,
        mc.number_seats AS places_total,
        mc.remaining_seats AS places_free,
        mc.info_all_contact_count AS applicants_total,
        mc.info_contact_visit AS applicants_visited,
        mc.info_contact_paid AS applicants_paid,
        mc.info_contact_visit_free AS applicants_free,
        mc.calendar_hidden,
        mc.parent_id,
        mc.filial AS branches,
        mc.audience,
        GROUP_CONCAT(mce.employee_id SEPARATOR ",") AS "teachers_hash"';
  }


}