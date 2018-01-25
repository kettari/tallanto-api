<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 06.01.2017
 * Time: 16:11
 */

namespace Tallanto\Api\Entity;


use Tallanto\Api\ExpandableInterface;
use Tallanto\Api\ExpandableTrait;

class Ticket extends AbstractIdentifiableEntity implements ExpandableInterface {

  use ExpandableTrait, BranchesTrait;

  /**
   * @var string
   */
  protected $name;

  /**
   * @var string
   */
  protected $date_start;

  /**
   * @var string
   */
  protected $date_finish;

  /**
   * @var string
   */
  protected $template_name;

  /**
   * @var string
   */
  protected $template_id;

  /**
   * @var
   */
  protected $template;

  /**
   * @var string
   */
  protected $contact_id;

  /**
   * @var Contact
   */
  protected $contact;

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
   * @var string
   */
  protected $manager_id;

  /**
   * @var User
   */
  protected $manager;

  /**
   * Ticket constructor.
   *
   * @param array $data
   */
  public function __construct($data) {
    parent::__construct($data);

    // Sanitize branch
    $this->branches = $this->sanitizeBranch($this->branches);

    // Build Template objects
    if (isset($data['template']) && !is_null($data['template'])) {
      $this->template = new Template($data['template']);
      // Expanded variables are provided, set the flag
      $this->setExpand(TRUE);
    }
    // Build Contact objects
    if (isset($data['contact']) && !is_null($data['contact'])) {
      $this->contact = new Contact($data['contact']);
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
    return $this->date_start;
  }

  /**
   * @param string $date_start
   * @return Ticket
   */
  public function setStartDate($date_start) {
    $this->date_start = $date_start;
    return $this;
  }

  /**
   * @return string
   */
  public function getFinishDate() {
    return $this->date_finish;
  }

  /**
   * @param string $date_finish
   * @return Ticket
   */
  public function setFinishDate($date_finish) {
    $this->date_finish = $date_finish;
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
  public function getContactId() {
    return $this->contact_id;
  }

  /**
   * @param string $contact_id
   * @return Ticket
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
   * @return Ticket
   */
  public function setContact($contact) {
    $this->contact = $contact;
    return $this;
  }

  /**
   * @return \Tallanto\Api\Entity\Template
   */
  public function getTemplate() {
    return $this->template;
  }

  /**
   * @param \Tallanto\Api\Entity\Template $template
   * @return Ticket
   */
  public function setTemplate($template) {
    $this->template = $template;
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