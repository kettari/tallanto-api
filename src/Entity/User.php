<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 07.01.2017
 * Time: 18:52
 */

namespace Tallanto\Api\Entity;


class User extends Person {

  /**
   * @var string
   */
  protected $user_name;

  /**
   * @return string
   */
  public function getUserName() {
    return $this->user_name;
  }

  /**
   * @param string $user_name
   * @return User
   */
  public function setUserName($user_name) {
    $this->user_name = $user_name;
    return $this;
  }

}