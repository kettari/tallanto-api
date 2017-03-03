<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 08.02.2017
 * Time: 14:35
 */

namespace Tallanto\Api\Entity;


class Branch extends AbstractEntity {

  /**
   * @var string
   */
  protected $key;

  /**
   * @var string
   */
  protected $value;

  /**
   * @return string
   */
  public function getKey() {
    return $this->key;
  }

  /**
   * @param string $key
   * @return Branch
   */
  public function setKey($key) {
    $this->key = $key;

    return $this;
  }

  /**
   * @return string
   */
  public function getValue() {
    return $this->value;
  }

  /**
   * @param string $value
   * @return Branch
   */
  public function setValue($value) {
    $this->value = $value;

    return $this;
  }

}