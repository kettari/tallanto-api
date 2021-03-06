<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 10.02.2017
 * Time: 23:28
 */

namespace Tallanto\Api\Provider\Database;


use Doctrine\DBAL\Connection;

class Factory {

  /**
   * Creates database provider according to provided class.
   *
   * @param string $for_aggregator_class
   * @param \Doctrine\DBAL\Connection $connection
   * @return \Tallanto\Api\Provider\Database\AbstractDatabaseProvider
   * @throws \Exception
   */
  public static function build($for_aggregator_class, Connection $connection) {
    switch ($for_aggregator_class) {
      case 'ContactAggregator':
        return new ContactDatabaseProvider($connection);
      case 'ContactTempAggregator':
        return new ContactTempDatabaseProvider($connection);
      case 'TicketAggregator':
        return new TicketDatabaseProvider($connection);
      case 'TemplateAggregator':
        return new TemplateDatabaseProvider($connection);
      case 'ContactVisitAggregator':
        return new ContactVisitDatabaseProvider($connection);
      case 'TicketVisitAggregator':
        return new TicketVisitDatabaseProvider($connection);
      case 'ClassVisitAggregator':
        return new ClassVisitDatabaseProvider($connection);
      case 'UserAggregator':
        return new UserDatabaseProvider($connection);
      case 'UserClassAggregator':
        return new UserClassDatabaseProvider($connection);
      case 'UserPayrollAggregator':
        return new UserPayrollDatabaseProvider($connection);
      case 'ClassAggregator':
        return new ClassDatabaseProvider($connection);
      case 'SubjectAggregator':
        return new SubjectDatabaseProvider($connection);
      case 'AudienceAggregator':
        return new AudienceDatabaseProvider($connection);
      case 'BranchAggregator':
        return new BranchDatabaseProvider($connection);
    }

    throw new \Exception('Unknown aggregator class, unable to build database provider.');
  }

}