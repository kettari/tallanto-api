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

class TicketDatabaseProvider extends AbstractDatabaseProvider implements ExpandableInterface
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
    $tickets = parent::fetch();

    // Expand Tickets
    if ($this->isExpand()) {
      // Template
      $template_provider = new TemplateDatabaseProvider($this->connection);
      $template_provider->setQueryDisableLike(true);
      // Contact
      $contact_provider = new ContactDatabaseProvider($this->connection);
      // Set Expand recursively
      $contact_provider->setExpand(true)
        ->setQueryDisableLike(true);
      // User
      $user_provider = new UserDatabaseProvider($this->connection);
      $user_provider->setQueryDisableLike(true);
      // Set Expand recursively
      $user_provider->setExpand(true);

      // Local copies
      $local_copy = $this->getLocalCopy();

      foreach ($tickets as $key => $item) {
        // Template
        if (is_null($local_copy->getCache('templates', $item['template_id'])) &&
          !is_null($item['template_id'])
        ) {
          $template_provider->setQuery($item['template_id']);
          if ($template = $template_provider->fetch()) {
            $local_copy->setCache(
              'templates',
              $item['template_id'],
              reset($template)
            );
          }
        }
        $tickets[$key]['template'] = $local_copy->getCache(
          'templates',
          $item['template_id']
        );
        // Contact
        if (is_null($local_copy->getCache('contacts', $item['contact_id'])) &&
          !is_null($item['contact_id'])
        ) {
          $contact_provider->setQuery($item['contact_id']);
          if ($contact = $contact_provider->fetch()) {
            $local_copy->setCache(
              'contacts',
              $item['contact_id'],
              reset($contact)
            );
          }
        }
        $tickets[$key]['contact'] = $local_copy->getCache(
          'contacts',
          $item['contact_id']
        );
        // Manager
        if (is_null($local_copy->getCache('users', $item['manager_id']))) {
          $user_provider->setQuery($item['manager_id']);
          if ($manager = $user_provider->fetch()) {
            $local_copy->setCache(
              'users',
              $item['manager_id'],
              reset($manager)
            );
          }
        }
        $tickets[$key]['manager'] = $local_copy->getCache(
          'users',
          $item['manager_id']
        );
      }
    }

    return $tickets;
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
    
      FROM most_abonements a
      
      LEFT JOIN most_template_abonements ta ON ta.id = a.template_id
      
      WHERE a.deleted = 0%s
      ORDER BY a.name, a.finish_date
      
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
    return 'a.id AS id,
        DATE_FORMAT(a.date_entered, "%Y-%m-%dT%H:%i:%sZ") AS "date_created",
        DATE_FORMAT(a.date_modified, "%Y-%m-%dT%H:%i:%sZ") AS "date_updated",
        a.name,
        DATE_FORMAT(a.start_date, "%Y-%m-%dT%H:%i:%sZ") AS "date_start",
        DATE_FORMAT(a.finish_date, "%Y-%m-%dT%H:%i:%sZ") AS "date_finish",
        ta.name AS template_name,
        a.template_id,
        a.contact_id,
        a.cost,
        a.cost_standard,
        a.duration,
        a.num_visit,
        a.num_visit_left,
        a.manual_closed,
        a.assigned_user_id AS manager_id,
        a.filial AS branches';
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
          ' AND 
        ( DATE_FORMAT(a.date_entered, "%s") = :query_exact OR
          DATE_FORMAT(a.date_modified, "%s") = :query_exact OR
          DATE_FORMAT(a.start_date, "%s") = :query_exact OR
          DATE_FORMAT(a.finish_date, "%s") = :query_exact )',
          $date_format,
          $date_format,
          $date_format,
          $date_format
        );

      } else {
        // Query is not date
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
    }
    if (!is_null($this->getIfModifiedSince())) {
      $where_clause .= ' AND a.date_modified > :modified_since';
    }

    return $where_clause;
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
      'COUNT(DISTINCT a.id)',
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


}