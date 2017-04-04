<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 04.04.2017
 * Time: 13:59
 */

namespace Tallanto\Api\Entity;


class ScheduleLevelEntity extends AbstractEntity
{

  /**
   * @var string
   */
  protected $id;

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
   * @return ScheduleLevelEntity
   */
  public function setId(string $id): ScheduleLevelEntity
  {
    $this->id = $id;

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
   * @return ScheduleLevelEntity
   */
  public function setTitle(string $title): ScheduleLevelEntity
  {
    $this->title = $title;

    return $this;
  }

}