<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 06.01.2017
 * Time: 16:11
 */

namespace Tallanto\Api\Entity;


class Ticket extends BaseEntity {

  /**
   * @var string
   */
  protected $name;

  /**
   * @var string
   */
  protected $start_date;

  /**
   * @var string
   */
  protected $finish_date;

  /**
   * @var string
   */
  protected $template_name;

  /**
   * @var string
   */
  protected $template_id;

  /**
   * @var string
   */
  protected $owner_id;

  /**
   * @var Contact
   */
  protected $owner;

  /**
   * @var integer
   */
  protected $cost;

  /**
   * @var integer
   */
  protected $cost_standard;

  /**
   * @var integer
   */
  protected $duration;

  /**
   * @var float
   */
  protected $num_visit;

  /**
   * @var float
   */
  protected $num_visit_left;

  /**
   * @var integer
   */
  protected $manual_closed;

  /**
   * @var array
   */
  protected $branches;

  /**
   * @var string
   */
  protected $manager_id;

  /**
   * @var User
   */
  protected $manager;

  /**
   * @return string
   */
  public function getName() {
    return $this->name;
  }

  /**
   * @param string $name
   * @return Ticket
   */
  public function setName($name) {
    $this->name = $name;
    return $this;
  }

  /**
   * @return string
   */
  public function getStartDate() {
    return $this->start_date;
  }

  /**
   * @param string $start_date
   * @return Ticket
   */
  public function setStartDate($start_date) {
    $this->start_date = $start_date;
    return $this;
  }

  /**
   * @return string
   */
  public function getFinishDate() {
    return $this->finish_date;
  }

  /**
   * @param string $finish_date
   * @return Ticket
   */
  public function setFinishDate($finish_date) {
    $this->finish_date = $finish_date;
    return $this;
  }

  /**
   * @return string
   */
  public function getTemplateName() {
    return $this->template_name;
  }

  /**
   * @param string $template_name
   * @return Ticket
   */
  public function setTemplateName($template_name) {
    $this->template_name = $template_name;
    return $this;
  }

  /**
   * @return string
   */
  public function getTemplateId() {
    return $this->template_id;
  }

  /**
   * @param string $template_id
   * @return Ticket
   */
  public function setTemplateId($template_id) {
    $this->template_id = $template_id;
    return $this;
  }

  /**
   * @return string
   */
  public function getOwnerId() {
    return $this->owner_id;
  }

  /**
   * @param string $owner_id
   * @return Ticket
   */
  public function setOwnerId($owner_id) {
    $this->owner_id = $owner_id;
    return $this;
  }

  /**
   * @return \Tallanto\Api\Entity\Contact
   */
  public function getOwner() {
    return $this->owner;
  }

  /**
   * @param \Tallanto\Api\Entity\Contact $owner
   * @return Ticket
   */
  public function setOwner($owner) {
    $this->owner = $owner;
    return $this;
  }

  /**
   * @return int
   */
  public function getCost() {
    return $this->cost;
  }

  /**
   * @param int $cost
   * @return Ticket
   */
  public function setCost($cost) {
    $this->cost = $cost;
    return $this;
  }

  /**
   * @return int
   */
  public function getCostStandard() {
    return $this->cost_standard;
  }

  /**
   * @param int $cost_standard
   * @return Ticket
   */
  public function setCostStandard($cost_standard) {
    $this->cost_standard = $cost_standard;
    return $this;
  }

  /**
   * @return int
   */
  public function getDuration() {
    return $this->duration;
  }

  /**
   * @param int $duration
   * @return Ticket
   */
  public function setDuration($duration) {
    $this->duration = $duration;
    return $this;
  }

  /**
   * @return float
   */
  public function getNumVisit() {
    return $this->num_visit;
  }

  /**
   * @param float $num_visit
   * @return Ticket
   */
  public function setNumVisit($num_visit) {
    $this->num_visit = $num_visit;
    return $this;
  }

  /**
   * @return float
   */
  public function getNumVisitLeft() {
    return $this->num_visit_left;
  }

  /**
   * @param float $num_visit_left
   * @return Ticket
   */
  public function setNumVisitLeft($num_visit_left) {
    $this->num_visit_left = $num_visit_left;
    return $this;
  }

  /**
   * @return int
   */
  public function getManualClosed() {
    return $this->manual_closed;
  }

  /**
   * @param int $manual_closed
   * @return Ticket
   */
  public function setManualClosed($manual_closed) {
    $this->manual_closed = $manual_closed;
    return $this;
  }

  /**
   * @return array
   */
  public function getBranches() {
    return $this->branches;
  }

  /**
   * @param array $branches
   * @return Ticket
   */
  public function setBranches($branches) {
    $this->branches = $branches;

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
   * @return Ticket
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
   * @return Ticket
   */
  public function setManager($manager) {
    $this->manager = $manager;
    return $this;
  }

}