<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 05.01.2017
 * Time: 21:54
 */

namespace Tallanto\Api;


use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Monolog\Logger;

class DatabaseClient {

  /**
   * @var \Monolog\Logger
   */
  protected $logger;

  /**
   * @var \Doctrine\DBAL\Connection
   */
  protected $connection;

  /**
   * DatabaseClient constructor.
   *
   * @param \Monolog\Logger $logger
   * @param string $database_name
   * @param string $user
   * @param string $password
   * @param string $host
   * @param int $port
   */
  public function __construct(Logger $logger, $database_name, $user, $password, $host = 'localhost', $port = 3306) {
    $this->logger = $logger;

    // DBAL configuration
    $config = new Configuration();
    $config->setSQLLogger(new TallantoSqlLogger($logger));

    // The connection configuration
    $connection_params = [
      'url' => sprintf('mysql://%s:%s@%s:%d/%s', $user, $password, $host, $port, $database_name),
    ];
    $this->connection = DriverManager::getConnection($connection_params, $config);

    // Write log
    $log_database_params = [
      'database_name' => $database_name,
      'user'          => $user,
      'password'      => 'HIDDEN',
      'host'          => $host,
      'port'          => $port,
    ];
    if ($this->connection->isConnected()) {
      $this->logger->debug('Connection to database "{database_name}" opened', $log_database_params);
    }
    else {
      $this->logger->error('Connection to database "{database_name}" is NOT opened', $log_database_params);
    }
  }

  /**
   * @return \Doctrine\DBAL\Connection
   */
  public function getConnection() {
    return $this->connection;
  }


}