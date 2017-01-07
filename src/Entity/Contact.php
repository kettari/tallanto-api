<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 06.01.2017
 * Time: 15:17
 */

namespace Tallanto\Api\Entity;


class Contact extends BaseEntity {
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
   * @var string
   */
  protected $phone_home;

  /**
   * @var string
   */
  protected $phone_mobile;

  /**
   * @var string
   */
  protected $phone_work;

  /**
   * @var string
   */
  protected $phone_other;

  /**
   * @var string
   */
  protected $phone_fax;

  /**
   * @var string
   */
  protected $type_client_c;

  /**
   * @var array
   */
  protected $email_addresses;

  /**
   * @var string
   */
  protected $manager_first_name;

  /**
   * @var string
   */
  protected $manager_last_name;

  /**
   * @var string
   */
  protected $type_client_translated;

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

  /**
   * @return string
   */
  public function getPhoneHome() {
    return $this->phone_home;
  }

  /**
   * @return string
   */
  public function getPhoneMobile() {
    return $this->phone_mobile;
  }

  /**
   * @return string
   */
  public function getPhoneWork() {
    return $this->phone_work;
  }

  /**
   * @return string
   */
  public function getPhoneOther() {
    return $this->phone_other;
  }

  /**
   * @return string
   */
  public function getPhoneFax() {
    return $this->phone_fax;
  }

  /**
   * @return string
   */
  public function getTypeClientC() {
    return $this->type_client_c;
  }

  /**
   * @return array
   */
  public function getEmailAddresses() {
    return $this->email_addresses;
  }

  /**
   * @return string
   */
  public function getManagerFirstName() {
    return $this->manager_first_name;
  }

  /**
   * @return string
   */
  public function getManagerLastName() {
    return $this->manager_last_name;
  }

  /**
   * @return string
   */
  public function getTypeClientTranslated() {
    return $this->type_client_translated;
  }

}