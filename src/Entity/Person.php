<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 08.02.2017
 * Time: 14:44
 */

namespace Tallanto\Api\Entity;


class Person extends BaseEntity {

  /**
   * @var string
   */
  protected $first_name;

  /**
   * @var string
   */
  protected $last_name;

  /**
   * @var string
   */
  protected $phone_mobile;

  /**
   * @var string
   */
  protected $phone_work;

  /**
   * @var array
   */
  protected $emails;

  /**
   * @var array
   */
  protected $branches;

  /**
   * @return string
   */
  public function getFirstName() {
    return $this->first_name;
  }

  /**
   * @param string $first_name
   * @return Person
   */
  public function setFirstName($first_name) {
    $this->first_name = $first_name;
    return $this;
  }

  /**
   * @return string
   */
  public function getLastName() {
    return $this->last_name;
  }

  /**
   * @param string $last_name
   * @return Person
   */
  public function setLastName($last_name) {
    $this->last_name = $last_name;
    return $this;
  }

  /**
   * @return string
   */
  public function getPhoneMobile() {
    return $this->phone_mobile;
  }

  /**
   * @param string $phone_mobile
   * @return Person
   */
  public function setPhoneMobile($phone_mobile) {
    $this->phone_mobile = $phone_mobile;
    return $this;
  }

  /**
   * @return string
   */
  public function getPhoneWork() {
    return $this->phone_work;
  }

  /**
   * @param string $phone_work
   * @return Person
   */
  public function setPhoneWork($phone_work) {
    $this->phone_work = $phone_work;
    return $this;
  }

  /**
   * @return array
   */
  public function getEmails() {
    return $this->emails;
  }

  /**
   * @param array $emails
   * @return Person
   */
  public function setEmails($emails) {
    $this->emails = $emails;
    return $this;
  }

  /**
   * @return array
   */
  public function getBranches() {
    return $this->branches;
  }

  /**
   * @param array $branches
   * @return Person
   */
  public function setBranches($branches) {
    $this->branches = $branches;

    return $this;
  }

}