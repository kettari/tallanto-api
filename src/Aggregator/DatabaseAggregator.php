<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 08.02.2017
 * Time: 15:22
 */

namespace Tallanto\Api\Aggregator;

use Tallanto\Api\DatabaseClient;

abstract class DatabaseAggregator extends AbstractAggregator {

  /**
   * @var int
   */
  protected $offset = 0;

  /**
   * @var int
   */
  protected $limit = 20;

  /**
   * @var string
   */
  protected $id;

  /**
   * @var string
   */
  protected $query;

  /**
   * @var DatabaseClient
   */
  protected $connection;

  /**
   * GeneralAggregator constructor.
   *
   * @param \Tallanto\Api\DatabaseClient $connection
   */
  public function __construct(DatabaseClient $connection) {
    $this->connection = $connection;
  }

  /**
   * Fetch (load) data from the database using LIMIT and possible ID and query
   * values.
   *
   * @return $this
   */
  abstract function fetch();

  /**
   * @return mixed
   */
  public function getOffset() {
    return $this->offset;
  }

  /**
   * @param mixed $offset
   * @return DatabaseAggregator
   */
  public function setOffset($offset) {
    $this->offset = $offset;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getLimit() {
    return $this->limit;
  }

  /**
   * @param mixed $limit
   * @return DatabaseAggregator
   */
  public function setLimit($limit) {
    $this->limit = $limit;
    return $this;
  }

  /**
   * @return string
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @param string $id
   * @return DatabaseAggregator
   */
  public function setId($id) {
    $this->id = $id;
    return $this;
  }

  /**
   * @return string
   */
  public function getQuery() {
    return $this->query;
  }

  /**
   * @param string $query
   * @return DatabaseAggregator
   */
  public function setQuery($query) {
    $this->query = $query;
    return $this;
  }

}