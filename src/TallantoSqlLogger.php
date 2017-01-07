<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 06.01.2017
 * Time: 20:59
 */

namespace Tallanto\Api;


use Doctrine\DBAL\Logging\DebugStack;
use Monolog\Logger;

class TallantoSqlLogger extends DebugStack {

  /**
   * @var \Monolog\Logger
   */
  protected $logger;

  /**
   * TallantoSqlLogger constructor.
   *
   * @param \Monolog\Logger $logger
   */
  public function __construct(Logger $logger) {
    $this->logger = $logger;
  }

  /**
   * @inheritdoc
   */
  public function startQuery($sql, array $params = NULL, array $types = NULL) {
    parent::startQuery($sql, $params, $types);
    $this->logger->debug(
      'SQL execution started',
      [
        'sql'    => $sql,
        'params' => $params,
        'types'  => $types,
      ]
    );
  }

  /**
   * @inheritdoc
   */
  public function stopQuery() {
    parent::stopQuery();

    // Measure time
    $execution_time = 0.0;
    if (isset($this->queries[$this->currentQuery])) {
      $last_query = $this->queries[$this->currentQuery];
      if (isset($last_query['executionMS'])) {
        $execution_time = $last_query['executionMS'];
      }
    }

    $this->logger->debug(
      'SQL execution finished, elapsed time {execution_time} seconds',
      [
        'execution_time' => sprintf('%.2f', $execution_time),
      ]
    );
  }


}