<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 08.02.2017
 * Time: 14:35
 */

namespace Tallanto\Api\Entity;


class Audience extends AbstractEntity {

  /**
   * @var string
   */
  protected $name;

  /**
   * @var string
   */
  protected $title;

  /**
   * @return string
   */
  public function getName() {
    return $this->name;
  }

  /**
   * @param string $name
   * @return Audience
   */
  public function setName($name) {
    $this->name = $name;

    return $this;
  }

  /**
   * @return string
   */
  public function getTitle() {
    return $this->title;
  }

  /**
   * @param string $title
   * @return Audience
   */
  public function setTitle($title) {
    $this->title = $title;

    return $this;
  }

}