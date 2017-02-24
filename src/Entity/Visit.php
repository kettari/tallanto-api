<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 06.01.2017
 * Time: 15:58
 */

namespace Tallanto\Api\Entity;


class Visit extends BaseEntity {

  /**
   * @var string
   */
  protected $class_id;

  /**
   * @var string
   */
  protected $contact_id;

  /**
   * @var string
   */
  protected $ticket_id;

  /**
   * @var string
   */
  protected $status;

  /**
   * @var string
   */
  protected $manager_id;

  /**
   * @var bool
   */
  protected $self_service;

  /**
   * @return string
   */
  public function getClassId() {
    return $this->class_id;
  }

  /**
   * @return string
   */
  public function getContactId() {
    return $this->contact_id;
  }

  /**
   * @return string
   */
  public function getTicketId() {
    return $this->ticket_id;
  }

  /**
   * @return string
   */
  public function getStatus() {
    return $this->status;
  }

  /**
   * @return string
   */
  public function getManagerId() {
    return $this->manager_id;
  }

  /**
   * @return bool
   */
  public function isSelfService() {
    return $this->self_service;
  }
}