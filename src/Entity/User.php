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
   * @var string
   */
  protected $user_status;

  /**
   * @var string
   */
  protected $employee_status;

  /**
   * @var float
   */
  protected $balance;

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

  /**
   * @return string
   */
  public function getUserStatus() {
    return $this->user_status;
  }

  /**
   * @param string $user_status
   * @return User
   */
  public function setUserStatus($user_status) {
    $this->user_status = $user_status;

    return $this;
  }

  /**
   * @return string
   */
  public function getEmployeeStatus() {
    return $this->employee_status;
  }

  /**
   * @param string $employee_status
   * @return User
   */
  public function setEmployeeStatus($employee_status) {
    $this->employee_status = $employee_status;

    return $this;
  }

  /**
   * @return float
   */
  public function getBalance() {
    return $this->balance;
  }

  /**
   * @param float $balance
   * @return User
   */
  public function setBalance($balance) {
    $this->balance = $balance;

    return $this;
  }

}