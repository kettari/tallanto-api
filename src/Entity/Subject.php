<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 27.02.2017
 * Time: 14:19
 */

namespace Tallanto\Api\Entity;


class Subject extends AbstractIdentifiableEntity {

  use BranchesTrait;

  /**
   * @var string
   */
  protected $name;

  /**
   * @var string
   */
  protected $description;

  /**
   * @var string
   */
  protected $status;

  /**
   * @var bool
   */
  protected $calendar_hidden;

  /**
   * @var string
   */
  protected $default_stake_id;

  /**
   * @var string
   */
  protected $date_start;

  /**
   * @var string
   */
  protected $date_finish;

  /**
   * Subject constructor.
   *
   * @param array $data
   */
  public function __construct($data) {
    parent::__construct($data);

    // Correct boolean to look like boolean
    $this->calendar_hidden = $this->calendar_hidden ? TRUE : FALSE;
    // Sanitize branch
    $this->branches = $this->sanitizeBranch($this->branches);
  }

  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * @param string $name
   * @return Subject
   */
  public function setName(string $name): Subject
  {
    $this->name = $name;

    return $this;
  }

  /**
   * @return string
   */
  public function getDescription()
  {
    return $this->description;
  }

  /**
   * @param string $description
   * @return Subject
   */
  public function setDescription(string $description): Subject
  {
    $this->description = $description;

    return $this;
  }

  /**
   * @return string
   */
  public function getStatus()
  {
    return $this->status;
  }

  /**
   * @param string $status
   * @return Subject
   */
  public function setStatus(string $status): Subject
  {
    $this->status = $status;

    return $this;
  }

  /**
   * @return bool
   */
  public function isCalendarHidden()
  {
    return $this->calendar_hidden;
  }

  /**
   * @param bool $calendar_hidden
   * @return Subject
   */
  public function setCalendarHidden(bool $calendar_hidden): Subject
  {
    $this->calendar_hidden = $calendar_hidden;

    return $this;
  }

  /**
   * @return string
   */
  public function getDefaultStakeId()
  {
    return $this->default_stake_id;
  }

  /**
   * @param string $default_stake_id
   * @return Subject
   */
  public function setDefaultStakeId(string $default_stake_id): Subject
  {
    $this->default_stake_id = $default_stake_id;

    return $this;
  }

  /**
   * @return string
   */
  public function getDateStart()
  {
    return $this->date_start;
  }

  /**
   * @param string $date_start
   * @return Subject
   */
  public function setDateStart(string $date_start): Subject
  {
    $this->date_start = $date_start;

    return $this;
  }

  /**
   * @return string
   */
  public function getDateFinish()
  {
    return $this->date_finish;
  }

  /**
   * @param string $date_finish
   * @return Subject
   */
  public function setDateFinish(string $date_finish): Subject
  {
    $this->date_finish = $date_finish;

    return $this;
  }

}