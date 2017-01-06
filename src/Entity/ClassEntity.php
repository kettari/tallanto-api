<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 05.01.2017
 * Time: 23:41
 */

namespace Tallanto\Api\Entity;


class ClassEntity extends BaseEntity {
  /**
   * @var string
   */
  protected $id;

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
  protected $status;

  /**
   * @var string
   */
  protected $filial;

  /**
   * @var string
   */
  protected $audience;

  /**
   * @var string
   */
  protected $subject_name;

  /**
   * @var string
   */
  protected $filial_translated;

  /**
   * @var string
   */
  protected $audience_translated;

  /**
   * @var string
   */
  protected $employee_first_name;

  /**
   * @var string
   */
  protected $employee_last_name;

  /**
   * @var string
   */
  protected $employee_id;

  /**
   * @var array
   */
  protected $visits = [];

  /**
   * ClassEntity constructor.
   *
   * @param $data
   */
  public function __construct($data) {
    parent::__construct(__CLASS__, $data);
  }

  /**
   * @return string
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @return string
   */
  public function getName() {
    return $this->name;
  }

  /**
   * @return string
   */
  public function getDateStart() {
    return $this->date_start;
  }

  /**
   * @return string
   */
  public function getDateFinish() {
    return $this->date_finish;
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
  public function getFilial() {
    return $this->filial;
  }

  /**
   * @return string
   */
  public function getAudience() {
    return $this->audience;
  }

  /**
   * @return string
   */
  public function getSubjectName() {
    return $this->subject_name;
  }

  /**
   * @return string
   */
  public function getFilialTranslated() {
    return $this->filial_translated;
  }

  /**
   * @return string
   */
  public function getAudienceTranslated() {
    return $this->audience_translated;
  }

  /**
   * @return string
   */
  public function getEmployeeFirstName() {
    return $this->employee_first_name;
  }

  /**
   * @return string
   */
  public function getEmployeeLastName() {
    return $this->employee_last_name;
  }

  /**
   * @return string
   */
  public function getEmployeeId() {
    return $this->employee_id;
  }

  /**
   * @return array
   */
  public function getVisits() {
    return $this->visits;
  }

}