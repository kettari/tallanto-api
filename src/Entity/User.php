<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 07.01.2017
 * Time: 18:52
 */

namespace Tallanto\Api\Entity;


class User extends BaseEntity {

  /**
   * @var string
   */
  protected $id;

  /**
   * @var string
   */
  protected $first_name;

  /**
   * @var string
   */
  protected $last_name;

  /**
   * @return string
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @return string
   */
  public function getFirstName() {
    return $this->first_name;
  }

  /**
   * @return string
   */
  public function getLastName() {
    return $this->last_name;
  }

}