<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 06.01.2017
 * Time: 15:17
 */

namespace Tallanto\Api\Entity;


class Contact extends Person {

  /**
   * @var string
   */
  protected $type_client_c;

  /**
   * @var string
   */
  protected $type_client_translated;

  /**
   * @var string
   */
  protected $manager_id;

  /**
   * @var User
   */
  protected $manager;

  /**
   * @return string
   */
  public function getTypeClientC() {
    return $this->type_client_c;
  }

  /**
   * @param string $type_client_c
   * @return Contact
   */
  public function setTypeClientC($type_client_c) {
    $this->type_client_c = $type_client_c;
    return $this;
  }

  /**
   * @return string
   */
  public function getTypeClientTranslated() {
    return $this->type_client_translated;
  }

  /**
   * @param string $type_client_translated
   * @return Contact
   */
  public function setTypeClientTranslated($type_client_translated) {
    $this->type_client_translated = $type_client_translated;
    return $this;
  }

  /**
   * @return string
   */
  public function getManagerId() {
    return $this->manager_id;
  }

  /**
   * @param string $manager_id
   * @return Contact
   */
  public function setManagerId($manager_id) {
    $this->manager_id = $manager_id;
    return $this;
  }

  /**
   * @return \Tallanto\Api\Entity\User
   */
  public function getManager() {
    return $this->manager;
  }

  /**
   * @param \Tallanto\Api\Entity\User $manager
   * @return Contact
   */
  public function setManager($manager) {
    $this->manager = $manager;
    return $this;
  }

}