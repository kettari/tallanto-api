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
   * @var integer
   */
  protected $date_start;

  /**
   * @var integer
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
   * @var integer
   */
  protected $profit;

  /**
   * @var array
   */
  protected $teachers = [];

  /**
   * @var array
   */
  protected $visits = [];

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
   * @return integer
   */
  public function getDateStart() {
    return $this->date_start;
  }

  /**
   * @return integer
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
   * @return int
   */
  public function getProfit() {
    return $this->profit;
  }

  /**
   * @return array
   */
  public function getTeachers() {
    return $this->teachers;
  }

  /**
   * @return array
   */
  public function getVisits() {
    return $this->visits;
  }

  /**
   * @param array $visits
   * @return ClassEntity
   */
  public function setVisits(array $visits) {
    $this->visits = $visits;

    return $this;
  }

}