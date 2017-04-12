<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 10.02.2017
 * Time: 19:38
 */

namespace Tallanto\Api\Provider\Database;


use Doctrine\DBAL\Connection;
use PDO;
use Tallanto\Api\Provider\AbstractProvider;
use Tallanto\Api\Provider\ProviderInterface;

abstract class AbstractDatabaseProvider extends AbstractProvider implements ProviderInterface
{

  /**
   * @var Connection
   */
  protected $connection;

  /**
   * @var bool
   */
  protected $query_disable_like = false;

  /**
   * @var LocalCopySingleton
   */
  private $local_copy;

  /**
   * AbstractDatabaseProvider constructor.
   *
   * @param \Doctrine\DBAL\Connection $connection
   */
  public function __construct(Connection $connection)
  {
    $this->connection = $connection;
    $this->local_copy = LocalCopySingleton::getInstance();
  }

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
    $offset = ($this->getPageNumber() - 1) * $this->getPageSize();
    $sql = $this->getMainSql(
      $this->getSelectClause(),
      $this->getWhereClause(),
      $this->getLimitClause($offset, $this->getPageSize())
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

    // Fetch all results into associative array
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Fetches all rows from the upstream.
   *
   * Returns array if everything is OK.
   *
   * @param callable $callback Callback to invoke while fetching data.
   * @return array
   * @throws \Exception
   */
  public function fetchAll(callable $callback = null)
  {
    throw new \Exception('Database provider does not allow fetchAll() method.');
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
  abstract protected function getMainSql(
    $select_clause,
    $where_clause,
    $limit_clause
  );

  /**
   * Prepares SELECT SQL clause.
   *
   * @return string
   */
  abstract protected function getSelectClause();

  /**
   * Prepares WHERE SQL clause
   *
   * @return string
   */
  abstract protected function getWhereClause();

  /**
   * Gets LIMIT SQL clause
   *
   * @param integer $items_offset
   * @param integer $items_limit
   * @return string
   */
  protected function getLimitClause($items_offset, $items_limit)
  {
    return sprintf('LIMIT %d, %d', $items_offset, $items_limit);
  }

  /**
   * Returns total number of records that fulfil the criteria.
   *
   * @return int
   */
  abstract function totalCount();

  /**
   * Check if LIKE is disabled in WHERE clause.
   *
   * @return bool
   */
  public function isQueryDisableLike()
  {
    return $this->query_disable_like;
  }

  /**
   * Disable LIKE in WHERE clause. Useful when requesting resource by ID.
   *
   * @param bool $query_disable_like
   * @return AbstractDatabaseProvider
   */
  public function setQueryDisableLike($query_disable_like)
  {
    $this->query_disable_like = $query_disable_like;

    return $this;
  }

  /**
   * @return LocalCopySingleton
   */
  public function getLocalCopy()
  {
    return $this->local_copy;
  }

}