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
  protected $last_contact_date;
  /**
   * @var string
   */
  protected $type_client_c;
  /**
   * @var string
   */
  protected $email_address;
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
   * @var string
   */
  protected $last_class_date;
  /**
   * @var string
   */
  protected $date_entered;

  /**
   * ClassEntity constructor.
   *
   * @param $data
   */
  public function __construct($data) {
    parent::__construct(__CLASS__, $data);
  }

}