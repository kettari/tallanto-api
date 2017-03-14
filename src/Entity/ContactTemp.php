<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 06.01.2017
 * Time: 15:17
 */

namespace Tallanto\Api\Entity;


class ContactTemp extends Person {

  /**
   * @var string
   */
  protected $email;

  /**
   * Serializes the object to an array including protected properties.
   *
   * @return array
   */
  public function toArray() {
    $result = parent::toArray();
    // Cleanup unnecessary fields
    if (isset($result['emails'])) {
      unset($result['emails']);
    }
    if (isset($result['expand'])) {
      unset($result['expand']);
    }

    return $result;
  }

  /**
   * @return string
   */
  public function getEmail() {
    return $this->email;
  }

  /**
   * @param string $email
   * @return ContactTemp
   */
  public function setEmail($email) {
    $this->email = $email;

    return $this;
  }

}