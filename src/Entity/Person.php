<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 08.02.2017
 * Time: 14:44
 */

namespace Tallanto\Api\Entity;


use Tallanto\Api\ExpandableInterface;
use Tallanto\Api\ExpandableTrait;

class Person extends BaseEntity implements ExpandableInterface {

  use ExpandableTrait;

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
   * Person constructor.
   *
   * @param array $data
   */
  public function __construct($data) {
    parent::__construct($data);

    // Build Email objects
    if (isset($data['emails']) && !is_null($data['emails'])) {
      $email_objects = [];
      foreach ($data['emails'] as $email) {
        $email_objects[] = new Email($email);
      }
      $this->emails = $email_objects;
      // Expanded variables are provided, set the flag
      $this->setExpand(TRUE);
    }
  }

  /**
   * Serializes the object to an array
   *
   * @return array
   */
  function toArray() {
    $vars = get_object_vars($this);
    // Serialize Emails correctly
    if (is_array($this->emails)) {
      $vars['emails'] = [];
      /** @var Email $email */
      foreach ($this->emails as $email) {
        $vars['emails'][] = $email->toArray();
      }
    }

    return $vars;
  }

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