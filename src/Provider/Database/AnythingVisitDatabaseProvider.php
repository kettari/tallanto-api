<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 10.02.2017
 * Time: 19:38
 */

namespace Tallanto\Api\Provider\Database;


use Tallanto\Api\ExpandableInterface;
use Tallanto\Api\ExpandableTrait;

abstract class AnythingVisitDatabaseProvider extends AbstractVisitDatabaseProvider implements ExpandableInterface {

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
  function fetch() {
    $visits = parent::fetch();

    // Expand visits
    if ($this->isExpand()) {
      // Class
      $class_provider = new ClassDatabaseProvider($this->connection);
      // Set Expand recursively
      $class_provider->setExpand(TRUE)
        ->setQueryDisableLike(TRUE);
      // Contact
      $contact_provider = new ContactDatabaseProvider($this->connection);
      // Set Expand recursively
      $contact_provider->setExpand(TRUE)
        ->setQueryDisableLike(TRUE);
      // Ticket
      $ticket_provider = new TicketDatabaseProvider($this->connection);
      // Set Expand recursively
      $ticket_provider->setExpand(TRUE)
        ->setQueryDisableLike(TRUE);
      // User
      $user_provider = new UserDatabaseProvider($this->connection);
      $user_provider->setQueryDisableLike(TRUE);
      // Set Expand recursively
      $user_provider->setExpand(TRUE);
      foreach ($visits as $key => $item) {
        // Class
        $class_provider->setQuery($item['class_id']);
        if ($class = $class_provider->fetch()) {
          $visits[$key]['class'] = reset($class);
        }
        // Contact
        $contact_provider->setQuery($item['contact_id']);
        if ($contact = $contact_provider->fetch()) {
          $visits[$key]['contact'] = reset($contact);
        }
        // Ticket
        $ticket_provider->setQuery($item['ticket_id']);
        if ($ticket = $ticket_provider->fetch()) {
          $visits[$key]['ticket'] = reset($ticket);
        }
        // Manager
        $user_provider->setQuery($item['manager_id']);
        if ($manager = $user_provider->fetch()) {
          $visits[$key]['manager'] = reset($manager);
        }
      }
    }

    return $visits;
  }

}