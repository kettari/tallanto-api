<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 04.04.2017
 * Time: 14:01
 */

namespace Tallanto\Api\Entity;


class ScheduleTeacherEntity extends AbstractEntity
{

  /**
   * @var string
   */
  protected $id;

  /**
   * @var string
   */
  protected $url;

  /**
   * @var string
   */
  protected $name;

  /**
   * @return string
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param string $id
   * @return ScheduleTeacherEntity
   */
  public function setId(string $id): ScheduleTeacherEntity
  {
    $this->id = $id;

    return $this;
  }

  /**
   * @return string
   */
  public function getUrl()
  {
    return $this->url;
  }

  /**
   * @param string $url
   * @return ScheduleTeacherEntity
   */
  public function setUrl(string $url): ScheduleTeacherEntity
  {
    $this->url = $url;

    return $this;
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
   * @return ScheduleTeacherEntity
   */
  public function setName(string $name): ScheduleTeacherEntity
  {
    $this->name = $name;

    return $this;
  }
}