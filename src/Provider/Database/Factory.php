<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 10.02.2017
 * Time: 23:28
 */

namespace Tallanto\Api\Provider\Database;


class Factory {

  /**
   * Creates database provider according to provided class.
   *
   * @param string $for_aggregator_class
   * @param \Tallanto\Api\Provider\Database\DatabaseClient $connection
   * @return \Tallanto\Api\Provider\Database\AbstractDatabaseProvider
   * @throws \Exception
   */
  public static function build($for_aggregator_class, DatabaseClient $connection) {
    switch ($for_aggregator_class) {
      case 'ContactAggregator':
        return new ContactDatabaseProvider($connection);
      case 'TicketAggregator':
        return new TicketDatabaseProvider($connection);
    }

    throw new \Exception('Unknown aggregator class, unable to build database provider.');
  }

}