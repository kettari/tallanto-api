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

class ContactDatabaseProvider extends AbstractDatabaseProvider implements ExpandableInterface
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
    $contacts = parent::fetch();

    // Expand Contacts
    if ($this->isExpand()) {
      $user_provider = new UserDatabaseProvider($this->connection);
      $user_provider->setQueryDisableLike(true);
      // Set Expand recursively
      $user_provider->setExpand(true);
      $email_provider = (new ContactEmailDatabaseProvider(
        $this->connection
      ))->setQueryDisableLike(true);

      // Local copies
      $local_copy = $this->getLocalCopy();

      foreach ($contacts as $key => $item) {
        // Manager
        if (is_null($local_copy->getCache('users', $item['manager_id'])) &&
          !is_null($item['manager_id'])
        ) {
          $user_provider->setQuery($item['manager_id']);
          if ($manager = $user_provider->fetch()) {
            $local_copy->setCache(
              'users',
              $item['manager_id'],
              reset($manager)
            );
          }
        }
        $contacts[$key]['manager'] = $local_copy->getCache(
          'users',
          $item['manager_id']
        );
        // Email
        if (is_null($local_copy->getCache('emails', $item['id']))) {
          $email_provider->setQuery($item['id']);
          $local_copy->setCache(
            'emails',
            $item['id'],
            $email_provider->fetch()
          );
        }
        $contacts[$key]['emails'] = $local_copy->getCache(
          'emails',
          $item['id']
        );
      }
    }

    //dump($contacts);
    return $contacts;
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
    
      FROM contacts c
      
      LEFT JOIN contacts_cstm cs ON cs.id_c = c.id
      
      WHERE c.deleted = 0%s
      ORDER BY c.last_name, c.first_name
      
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
    return 'c.id AS id,
        c.first_name,
        c.last_name,
        c.phone_mobile,
        c.phone_work,
        c.last_contact_date,
        DATE_FORMAT(c.date_entered, "%Y-%m-%dT%H:%i:%sZ") AS "date_created",
        DATE_FORMAT(c.date_modified, "%Y-%m-%dT%H:%i:%sZ") AS "date_updated",
        c.assigned_user_id AS manager_id,
        cs.type_client_c AS `type`,
        c.filial AS branches';
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
        (c.id = :query_exact';
      if (!$this->isQueryDisableLike()) {
        $where_clause .= ' OR
          c.first_name LIKE :query_like OR 
          c.last_name LIKE :query_like OR
          c.phone_mobile LIKE :query_like OR
          c.phone_work LIKE :query_like';
      }
      $where_clause .= ')';
    }
    if (!is_null($this->getIfModifiedSince())) {
      $where_clause .= ' AND c.date_modified > :modified_since';
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
      'COUNT(DISTINCT c.id)',
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