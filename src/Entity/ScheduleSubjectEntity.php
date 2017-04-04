<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 04.04.2017
 * Time: 13:59
 */

namespace Tallanto\Api\Entity;


class ScheduleSubjectEntity extends AbstractEntity
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
  protected $title;

  /**
   * @return string
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param string $id
   * @return ScheduleSubjectEntity
   */
  public function setId(string $id): ScheduleSubjectEntity
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
   * @return ScheduleSubjectEntity
   */
  public function setUrl(string $url): ScheduleSubjectEntity
  {
    $this->url = $url;

    return $this;
  }

  /**
   * @return string
   */
  public function getTitle()
  {
    return $this->title;
  }

  /**
   * @param string $title
   * @return ScheduleSubjectEntity
   */
  public function setTitle(string $title): ScheduleSubjectEntity
  {
    $this->title = $title;

    return $this;
  }

}