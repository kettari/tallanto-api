<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 08.02.2017
 * Time: 14:35
 */

namespace Tallanto\Api\Entity;


class Email extends AbstractEntity {

  /**
   * @var string
   */
  protected $address;

  /**
   * @var bool
   */
  protected $main;

  /**
   * @var bool
   */
  protected $invalid;

  /**
   * @var bool
   */
  protected $opt_out;

  /**
   * AbstractEntity constructor.
   *
   * @param array $data
   */
  public function __construct(array $data) {
    parent::__construct($data);

    // Correct boolean to look like boolean
    $this->main = $this->main ? TRUE : FALSE;
    $this->invalid = $this->invalid ? TRUE : FALSE;
    $this->opt_out = $this->opt_out ? TRUE : FALSE;
  }


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

  /**
   * @return bool
   */
  public function isInvalid() {
    return $this->invalid;
  }

  /**
   * @param bool $invalid
   * @return Email
   */
  public function setInvalid($invalid) {
    $this->invalid = $invalid;

    return $this;
  }

  /**
   * @return bool
   */
  public function isOptOut() {
    return $this->opt_out;
  }

  /**
   * @param bool $opt_out
   * @return Email
   */
  public function setOptOut($opt_out) {
    $this->opt_out = $opt_out;

    return $this;
  }

}