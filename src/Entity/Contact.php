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
  protected $type;

  /**
   * @var string
   */
  protected $manager_id;

  /**
   * @var User
   */
  protected $manager;

  /**
   * Contact constructor.
   *
   * @param array $data
   */
  public function __construct($data) {
    parent::__construct($data);

    // Build User objects
    if (isset($data['manager']) && !is_null($data['manager'])) {
      $this->manager = new User($data['manager']);
      // Expanded variables are provided, set the flag
      $this->setExpand(TRUE);
    }
  }

  /**
   * @return string
   */
  public function getType() {
    return $this->type;
  }

  /**
   * @param string $type
   * @return Contact
   */
  public function setType($type) {
    $this->type = $type;
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