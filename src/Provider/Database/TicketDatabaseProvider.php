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

class TicketDatabaseProvider extends AbstractDatabaseProvider implements ExpandableInterface {

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
    $tickets = parent::fetch();

    // Expand Tickets
    if ($this->isExpand()) {
      // Template
      $template_provider = new TemplateDatabaseProvider($this->connection);
      $template_provider->setQueryDisableLike(TRUE);
      // Contact
      $contact_provider = new ContactDatabaseProvider($this->connection);
      // Set Expand recursively
      $contact_provider->setExpand(TRUE)
        ->setQueryDisableLike(TRUE);
      // User
      $user_provider = new UserDatabaseProvider($this->connection);
      $user_provider->setQueryDisableLike(TRUE);
      // Set Expand recursively
      $user_provider->setExpand(TRUE);
      foreach ($tickets as $key => $item) {
        // Template
        $template_provider->setQuery($item['template_id']);
        if ($template = $template_provider->fetch()) {
          $tickets[$key]['template'] = reset($template);
        }
        // Contact
        $contact_provider->setQuery($item['contact_id']);
        if ($contact = $contact_provider->fetch()) {
          $tickets[$key]['contact'] = reset($contact);
        }
        // Manager
        $user_provider->setQuery($item['manager_id']);
        if ($manager = $user_provider->fetch()) {
          $tickets[$key]['manager'] = reset($manager);
        }
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
  protected function getMainSql($select_clause, $where_clause, $limit_clause) {
    return sprintf('
      SELECT
        %s
    
      FROM most_abonements a
      
      LEFT JOIN most_template_abonements ta ON ta.id = a.template_id
      
      WHERE a.deleted = 0%s
      ORDER BY a.name, a.finish_date
      
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
    $stmt = $this->connection->executeQuery(
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