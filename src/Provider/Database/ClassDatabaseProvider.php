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

class ClassDatabaseProvider extends AbstractDatabaseProvider implements ExpandableInterface {

  use ExpandableTrait;

  /**
   * Fetches (loads) data from the upstream using page number, page size and
   * possible ID and query values.
   *
   * Returns array if everything is OK.
   *
   * @return array
   */
  function fetch() {
    $classes = parent::fetch();

    // Expand Classes
    if ($this->isExpand()) {
      // Expand Subject
      $subject_provider = new SubjectDatabaseProvider($this->connection);
      $subject_provider->setQueryDisableLike(TRUE);
      // Expand Teachers
      $teacher_provider = new TeacherDatabaseProvider($this->connection);
      $teacher_provider->setQueryDisableLike(TRUE);
      $email_provider = (new UserEmailDatabaseProvider($this->connection))->setQueryDisableLike(TRUE);
      foreach ($classes as $key_class => $class) {
        // Subject
        $subject_provider->setQuery($class['subject_id']);
        $subjects = $subject_provider->fetch();
        $classes[$key_class]['subject'] = reset($subjects);
        // Teacher
        $teacher_provider->setQuery($class['id']);
        if ($teachers = $teacher_provider->fetch()) {
          $classes[$key_class]['teachers'] = [];
          foreach ($teachers as $key_teacher => $teacher) {
            // Email
            $email_provider->setQuery($teacher['id']);
            $teacher['emails'] = $email_provider->fetch();
            // Assign result to the root array
            $classes[$key_class]['teachers'][] = $teacher;
          }
        }
      }
    }

    //dump($classes);
    return $classes;
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
      
      WHERE mc.deleted = 0%s
      
      %s', $select_clause, $where_clause, $limit_clause);
  }

  /**
   * Prepares SELECT SQL clause.
   *
   * @return string
   */
  protected function getSelectClause() {
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
        mc.audience';
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
        (mc.id = :query_exact';
      if (!$this->isQueryDisableLike()) {
        $where_clause .= ' OR
          mc.name LIKE :query_like OR 
          DATE_FORMAT(mc.date_start, "%Y-%m-%dT%H:%i:%sZ") = :query_exact';
      }
      $where_clause .= ')';
    }
    if (!is_null($this->getIfModifiedSince())) {
      $where_clause .= ' AND mc.date_modified > :modified_since';
    }

    return $where_clause;
  }

  /**
   * Returns total number of records that fulfil the criteria.
   *
   * @return int
   */
  function totalCount() {
    $sql = $this->getMainSql('COUNT(DISTINCT mc.id)', $this->getWhereClause(),
      '');
    $stmt = $this->connection->executeQuery($sql, [
      'query_like'     => '%'.$this->query.'%',
      'query_exact'    => $this->query,
      'modified_since' => !is_null($this->if_modified_since) ? $this->if_modified_since->format('Y-m-d H:i:s') : 0,
    ], [PDO::PARAM_STR, PDO::PARAM_STR, PDO::PARAM_STR]);

    // Fetch column, it contains records count
    return $stmt->fetchColumn();
  }


}