<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 08.02.2017
 * Time: 14:35
 */

namespace Tallanto\Api\Entity;


class Email extends BaseEntity {

  /**
   * @var string
   */
  protected $address;

  /**
   * @var bool
   */
  protected $main;

  /**
   * @return string
   */
  public function getAddress() {
    return $this->address;
  }

  /**
   * @param string $address
   * @return $this
   */
  public function setAddress($address) {
    $this->address = $address;
    return $this;
  }

  /**
   * @return bool
   */
  public function isMain() {
    return $this->main;
  }

  /**
   * @param mixed $main
   * @return Email
   */
  public function setMain($main) {
    $this->main = $main;
    return $this;
  }



}