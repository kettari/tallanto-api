<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 04.04.2017
 * Time: 13:59
 */

namespace Tallanto\Api\Entity;


class ScheduleClassEntity extends AbstractEntity {

  /**
   * @var string
   */
  protected $id;

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
  protected $subject_id;

  /**
   * @var string
   */
  protected $teacher_id;

  /**
   * @var string
   */
  protected $level_id;

  /**
   * @var bool
   */
  protected $signup_open;

  /**
   * @var bool
   */
  protected $allow_self_signup;

  /**
   * @return string
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @param string $id
   * @return ScheduleClassEntity
   */
  public function setId(string $id): ScheduleClassEntity {
    $this->id = $id;

    return $this;
  }

  /**
   * @return string
   */
  public function getDateStart() {
    return $this->date_start;
  }

  /**
   * @param string $date_start
   * @return ScheduleClassEntity
   */
  public function setDateStart(string $date_start): ScheduleClassEntity {
    $this->date_start = $date_start;

    return $this;
  }

  /**
   * @return string
   */
  public function getDateFinish() {
    return $this->date_finish;
  }

  /**
   * @param string $date_finish
   * @return ScheduleClassEntity
   */
  public function setDateFinish(string $date_finish): ScheduleClassEntity {
    $this->date_finish = $date_finish;

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
   * @return ScheduleClassEntity
   */
  public function setSubjectId(string $subject_id): ScheduleClassEntity {
    $this->subject_id = $subject_id;

    return $this;
  }

  /**
   * @return string
   */
  public function getTeacherId() {
    return $this->teacher_id;
  }

  /**
   * @param string $teacher_id
   * @return ScheduleClassEntity
   */
  public function setTeacherId(string $teacher_id): ScheduleClassEntity {
    $this->teacher_id = $teacher_id;

    return $this;
  }

  /**
   * @return string
   */
  public function getLevelId() {
    return $this->level_id;
  }

  /**
   * @param string $level_id
   * @return ScheduleClassEntity
   */
  public function setLevelId(string $level_id): ScheduleClassEntity {
    $this->level_id = $level_id;

    return $this;
  }

  /**
   * @return bool
   */
  public function isSignupOpen() {
    return $this->signup_open;
  }

  /**
   * @param bool $signup_open
   * @return ScheduleClassEntity
   */
  public function setSignupOpen(bool $signup_open): ScheduleClassEntity {
    $this->signup_open = $signup_open;

    return $this;
  }

  /**
   * @return bool
   */
  public function isAllowSelfSignup() {
    return $this->allow_self_signup;
  }

  /**
   * @param bool $allow_self_signup
   * @return ScheduleClassEntity
   */
  public function setAllowSelfSignup(bool $allow_self_signup): ScheduleClassEntity {
    $this->allow_self_signup = $allow_self_signup;

    return $this;
  }

}