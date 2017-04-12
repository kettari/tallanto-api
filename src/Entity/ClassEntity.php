<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 05.01.2017
 * Time: 23:41
 */

namespace Tallanto\Api\Entity;


use Tallanto\Api\ExpandableInterface;
use Tallanto\Api\ExpandableTrait;

class ClassEntity extends AbstractIdentifiableEntity implements ExpandableInterface {

  use ExpandableTrait, BranchesTrait;

  /**
   * @var string
   */
  protected $name;

  /**
   * @var string
   */
  protected $subject_id;

  /**
   * @var Subject
   */
  protected $subject;

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
  protected $status;

  /**
   * @var float
   */
  protected $cost;

  /**
   * @var float
   */
  protected $profit;

  /**
   * @var integer
   */
  protected $places_total;

  /**
   * @var integer
   */
  protected $places_free;

  /**
   * @var integer
   */
  protected $applicants_total;

  /**
   * @var integer
   */
  protected $applicants_visited;

  /**
   * @var integer
   */
  protected $applicants_paid;

  /**
   * @var integer
   */
  protected $applicants_free;

  /**
   * @var bool
   */
  protected $calendar_hidden;

  /**
   * @var string
   */
  protected $parent_id;

  /**
   * @var string
   */
  protected $audience;

  /**
   * @var array
   */
  protected $teachers;

  /**
   * @var string
   */
  protected $teachers_hash;

  /**
   * ClassEntity constructor.
   *
   * @param array $data
   */
  public function __construct($data) {
    parent::__construct($data);

    // Correct boolean to look like boolean
    $this->calendar_hidden = $this->calendar_hidden ? TRUE : FALSE;
    // Sanitize branch
    $this->branches = $this->sanitizeBranch($this->branches);
    // Sanitize audience
    $this->audience = $this->sanitizeAudience($this->audience);

    // Build Subject objects
    if (isset($data['subject']) && !is_null($data['subject'])) {
      $this->subject = new Subject($data['subject']);
      // Expanded variables are provided, set the flag
      $this->setExpand(TRUE);
    }
    // Build User objects
    if (isset($data['teachers']) && !is_null($data['teachers'])) {
      $teacher_objects = [];
      foreach ($data['teachers'] as $teacher) {
        $teacher_objects[] = new Teacher($teacher);
      }
      $this->teachers = $teacher_objects;
      //dump($this->teachers);
      // Expanded variables are provided, set the flag
      $this->setExpand(TRUE);
    }
  }

  /**
   * Serializes the object to an array
   *
   * @return array
   */
  function toArray() {
    $vars = parent::toArray();
    // Serialize User correctly
    if (is_array($this->teachers)) {
      $vars['teachers'] = [];
      /** @var User $teacher */
      foreach ($this->teachers as $teacher) {
        $vars['teachers'][] = $teacher->toArray();
      }
    }

    return $vars;
  }

  /**
   * @return string
   */
  public function getName() {
    return $this->name;
  }

  /**
   * @param string $name
   * @return ClassEntity
   */
  public function setName($name) {
    $this->name = $name;

    return $this;
  }

  /**
   * @return string
   */
  public function getSubjectId() {
    return $this->subject_id;
  }

  /**
   * @param string $subject_id
   * @return ClassEntity
   */
  public function setSubjectId($subject_id) {
    $this->subject_id = $subject_id;

    return $this;
  }

  /**
   * @return \Tallanto\Api\Entity\Subject
   */
  public function getSubject() {
    return $this->subject;
  }

  /**
   * @param \Tallanto\Api\Entity\Subject $subject
   * @return ClassEntity
   */
  public function setSubject($subject) {
    $this->subject = $subject;

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
   * @return ClassEntity
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
   * @return ClassEntity
   */
  public function setFinishDate($date_finish) {
    $this->date_finish = $date_finish;

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
   * @return ClassEntity
   */
  public function setStatus($status) {
    $this->status = $status;

    return $this;
  }

  /**
   * @return float
   */
  public function getCost() {
    return $this->cost;
  }

  /**
   * @param int $cost
   * @return ClassEntity
   */
  public function setCost($cost) {
    $this->cost = $cost;

    return $this;
  }

  /**
   * @return float
   */
  public function getProfit() {
    return $this->profit;
  }

  /**
   * @param float $profit
   * @return ClassEntity
   */
  public function setProfit($profit) {
    $this->profit = $profit;

    return $this;
  }

  /**
   * @return int
   */
  public function getPlacesTotal() {
    return $this->places_total;
  }

  /**
   * @param int $places_total
   * @return ClassEntity
   */
  public function setPlacesTotal($places_total) {
    $this->places_total = $places_total;

    return $this;
  }

  /**
   * @return int
   */
  public function getPlacesFree() {
    return $this->places_free;
  }

  /**
   * @param int $places_free
   * @return ClassEntity
   */
  public function setPlacesFree($places_free) {
    $this->places_free = $places_free;

    return $this;
  }

  /**
   * @return int
   */
  public function getApplicantsTotal() {
    return $this->applicants_total;
  }

  /**
   * @param int $applicants_total
   * @return ClassEntity
   */
  public function setApplicantsTotal($applicants_total) {
    $this->applicants_total = $applicants_total;

    return $this;
  }

  /**
   * @return int
   */
  public function getApplicantsVisited() {
    return $this->applicants_visited;
  }

  /**
   * @param int $applicants_visited
   * @return ClassEntity
   */
  public function setApplicantsVisited($applicants_visited) {
    $this->applicants_visited = $applicants_visited;

    return $this;
  }

  /**
   * @return int
   */
  public function getApplicantsPaid() {
    return $this->applicants_paid;
  }

  /**
   * @param int $applicants_paid
   * @return ClassEntity
   */
  public function setApplicantsPaid($applicants_paid) {
    $this->applicants_paid = $applicants_paid;

    return $this;
  }

  /**
   * @return int
   */
  public function getApplicantsFree() {
    return $this->applicants_free;
  }

  /**
   * @param int $applicants_free
   * @return ClassEntity
   */
  public function setApplicantsFree($applicants_free) {
    $this->applicants_free = $applicants_free;

    return $this;
  }

  /**
   * @return bool
   */
  public function isCalendarHidden() {
    return $this->calendar_hidden;
  }

  /**
   * @param bool $calendar_hidden
   * @return ClassEntity
   */
  public function setCalendarHidden($calendar_hidden) {
    $this->calendar_hidden = $calendar_hidden;

    return $this;
  }

  /**
   * @return string
   */
  public function getParentId() {
    return $this->parent_id;
  }

  /**
   * @param string $parent_id
   * @return ClassEntity
   */
  public function setParentId($parent_id) {
    $this->parent_id = $parent_id;

    return $this;
  }

  /**
   * @return string
   */
  public function getAudience() {
    return $this->audience;
  }

  /**
   * @param string $audience
   * @return ClassEntity
   */
  public function setAudience($audience) {
    $this->audience = $audience;

    return $this;
  }

  /**
   * @return array
   */
  public function getTeachers() {
    return $this->teachers;
  }

  /**
   * @param array $teachers
   * @return ClassEntity
   */
  public function setTeachers($teachers) {
    $this->teachers = $teachers;

    return $this;
  }

  /**
   * Sanitize audience and return string.
   *
   * @param $audience
   * @return string
   */
  private function sanitizeAudience($audience) {
    $audiences = [];
    if (preg_match('/\^([a-zA-Z0-9_\-]+)\^/', $audience, $matches)) {
      for ($i = 1; $i < count($matches); $i++) {
        $audiences[] = $matches[$i];
      }
    }

    return count($audiences) ? reset($audiences) : '';
  }

  /**
   * @return string
   */
  public function getTeachersHash()
  {
    return $this->teachers_hash;
  }

  /**
   * @param string $teachers_hash
   * @return ClassEntity
   */
  public function setTeachersHash($teachers_hash)
  {
    $this->teachers_hash = $teachers_hash;

    return $this;
  }

}