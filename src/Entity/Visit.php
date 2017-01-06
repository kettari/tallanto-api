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
   * @var ClassEntity
   */
  protected $class;

  /**
   * @var Contact
   */
  protected $contact;

  /**
   * @var Ticket
   */
  protected $ticket;

  /**
   * @var string
   */
  protected $status;

  /**
   * Visit constructor.
   *
   * @param $data
   */
  public function __construct($data) {
    parent::__construct(__CLASS__, $data);
  }

  /**
   * @return \Tallanto\Api\Entity\ClassEntity
   */
  public function getClass() {
    return $this->class;
  }

  /**
   * @return \Tallanto\Api\Entity\Contact
   */
  public function getContact() {
    return $this->contact;
  }

  /**
   * @return \Tallanto\Api\Entity\Ticket
   */
  public function getTicket() {
    return $this->ticket;
  }

  /**
   * @return string
   */
  public function getStatus() {
    return $this->status;
  }

}