<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 06.01.2017
 * Time: 15:58
 */

namespace Tallanto\Api\Entity;


use Tallanto\Api\ExpandableInterface;
use Tallanto\Api\ExpandableTrait;

class Visit extends AbstractIdentifiableEntity implements ExpandableInterface {

  use ExpandableTrait;

  /**
   * @var string
   */
  protected $class_id;

  /**
   * @var ClassEntity
   */
  protected $class;

  /**
   * @var string
   */
  protected $contact_id;

  /**
   * @var Contact
   */
  protected $contact;

  /**
   * @var string
   */
  protected $ticket_id;

  /**
   * @var Ticket
   */
  protected $ticket;

  /**
   * @var string
   */
  protected $status;

  /**
   * @var string
   */
  protected $manager_id;

  /**
   * @var User
   */
  protected $manager;

  /**
   * @var bool
   */
  protected $self_service;

  /**
   * Visit constructor.
   *
   * @param array $data
   */
  public function __construct(array $data) {
    parent::__construct($data);

    // Correct boolean to look like boolean
    $this->self_service = $this->self_service ? TRUE : FALSE;

    // Build Class objects
    if (isset($data['class']) && !is_null($data['class'])) {
      $this->class = new ClassEntity($data['class']);
      // Expanded variables are provided, set the flag
      $this->setExpand(TRUE);
    }
    // Build Contact objects
    if (isset($data['contact']) && !is_null($data['contact'])) {
      $this->contact = new Contact($data['contact']);
      // Expanded variables are provided, set the flag
      $this->setExpand(TRUE);
    }
    // Build Ticket objects
    if (isset($data['ticket']) && !is_null($data['ticket'])) {
      $this->contact = new Ticket($data['ticket']);
      // Expanded variables are provided, set the flag
      $this->setExpand(TRUE);
    }
    // Build User objects
    if (isset($data['manager']) && !is_null($data['manager'])) {
      $this->manager = new User($data['manager']);
      // Expanded variables are provided, set the flag
      $this->setExpand(TRUE);
    }
  }

  /**
   * @return string
   */
  public function getClassId() {
    return $this->class_id;
  }

  /**
   * @param string $class_id
   * @return Visit
   */
  public function setClassId($class_id) {
    $this->class_id = $class_id;

    return $this;
  }

  /**
   * @return \Tallanto\Api\Entity\ClassEntity
   */
  public function getClass() {
    return $this->class;
  }

  /**
   * @param \Tallanto\Api\Entity\ClassEntity $class
   * @return Visit
   */
  public function setClass($class) {
    $this->class = $class;

    return $this;
  }

  /**
   * @return string
   */
  public function getContactId() {
    return $this->contact_id;
  }

  /**
   * @param string $contact_id
   * @return Visit
   */
  public function setContactId($contact_id) {
    $this->contact_id = $contact_id;

    return $this;
  }

  /**
   * @return \Tallanto\Api\Entity\Contact
   */
  public function getContact() {
    return $this->contact;
  }

  /**
   * @param \Tallanto\Api\Entity\Contact $contact
   * @return Visit
   */
  public function setContact($contact) {
    $this->contact = $contact;

    return $this;
  }

  /**
   * @return string
   */
  public function getTicketId() {
    return $this->ticket_id;
  }

  /**
   * @param string $ticket_id
   * @return Visit
   */
  public function setTicketId($ticket_id) {
    $this->ticket_id = $ticket_id;

    return $this;
  }

  /**
   * @return \Tallanto\Api\Entity\Ticket
   */
  public function getTicket() {
    return $this->ticket;
  }

  /**
   * @param \Tallanto\Api\Entity\Ticket $ticket
   * @return Visit
   */
  public function setTicket($ticket) {
    $this->ticket = $ticket;

    return $this;
  }

  /**
   * @return string
   */
  public function getStatus() {
    return $this->status;
  }

  /**
   * @param string $status
   * @return Visit
   */
  public function setStatus($status) {
    $this->status = $status;

    return $this;
  }

  /**
   * @return string
   */
  public function getManagerId() {
    return $this->manager_id;
  }

  /**
   * @param string $manager_id
   * @return Visit
   */
  public function setManagerId($manager_id) {
    $this->manager_id = $manager_id;

    return $this;
  }

  /**
   * @return \Tallanto\Api\Entity\User
   */
  public function getManager() {
    return $this->manager;
  }

  /**
   * @param \Tallanto\Api\Entity\User $manager
   * @return Visit
   */
  public function setManager($manager) {
    $this->manager = $manager;

    return $this;
  }

  /**
   * @return bool
   */
  public function isSelfService() {
    return $this->self_service;
  }

  /**
   * @param bool $self_service
   * @return Visit
   */
  public function setSelfService($self_service) {
    $this->self_service = $self_service;

    return $this;
  }

}