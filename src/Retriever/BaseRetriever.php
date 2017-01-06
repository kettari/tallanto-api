<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 06.01.2017
 * Time: 19:59
 */

namespace Tallanto\Api\Retriever;


use Monolog\Logger;
use Tallanto\Api\DatabaseClient;

class BaseRetriever {

  /**
   * @var \Monolog\Logger
   */
  protected $logger;

  /**
   * @var DatabaseClient
   */
  protected $connection;

  /**
   * Aggregator constructor.
   *
   * @param \Monolog\Logger $logger
   * @param \Tallanto\Api\DatabaseClient $connection
   */
  public function __construct(Logger $logger, DatabaseClient $connection) {
    $this->logger = $logger;
    $this->connection = $connection;
  }

}