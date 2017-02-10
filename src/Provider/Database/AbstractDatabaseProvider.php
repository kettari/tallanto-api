<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 10.02.2017
 * Time: 19:38
 */

namespace Tallanto\Api\Provider\Database;


use PDO;
use Tallanto\Api\Provider\AbstractProvider;
use Tallanto\Api\Provider\ProviderInterface;

abstract class AbstractDatabaseProvider extends AbstractProvider implements ProviderInterface {

  /**
   * @var DatabaseClient
   */
  protected $connection;

  /**
   * AbstractDatabaseProvider constructor.
   *
   * @param \Tallanto\Api\Provider\Database\DatabaseClient $connection
   */
  public function __construct(DatabaseClient $connection) {
    $this->connection = $connection;
  }

  /**
   * Fetches (loads) data from the upstream using page number, page size and
   * possible ID and query values.
   *
   * Returns array if everything is OK.
   *
   * @return array
   */
  function fetch() {
    $offset = ($this->getPageNumber() - 1) * $this->getPageSize();
    $sql = $this->getMainSql(
      $this->getSelectClause(),
      $this->getWhereClause(),
      $this->getLimitClause($offset, $this->getPageSize())
    );
    $stmt = $this->connection->getConnection()->executeQuery(
      $sql,
      ['query' => '%' . $this->query . '%'],
      [
        PDO::PARAM_STR,
      ]
    );

    // Fetch all results into associative array
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
  abstract protected function getMainSql($select_clause, $where_clause, $limit_clause);

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
  protected function getLimitClause($items_offset, $items_limit) {
    return sprintf('LIMIT %d, %d', $items_offset, $items_limit);
  }

  /**
   * Returns total number of records that fulfil the criteria.
   *
   * @return int
   */
  abstract function totalCount();


}