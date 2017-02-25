<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 05.01.2017
 * Time: 23:44
 */

namespace Tallanto\Api\Entity;


abstract class BaseEntity {

  /**
   * @var string
   */
  protected $id;

  /**
   * @var string
   */
  protected $date_created;

  /**
   * @var string
   */
  protected $date_updated;

  /**
   * BaseEntity constructor.
   *
   * @param array $data
   */
  public function __construct($data) {
    foreach ($data as $key => $val) {
      if (property_exists($this, $key)) {
        $this->$key = $val;
      }
    }
  }

  /**
   * Serializes the object to an array
   *
   * @return array
   */
  function toArray() {
    return get_object_vars($this);
  }

  /**
   * @return string
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @param string $id
   * @return BaseEntity
   */
  public function setId($id) {
    $this->id = $id;
    return $this;
  }

  /**
   * @return string
   */
  public function getDateCreated() {
    return $this->date_created;
  }

  /**
   * @param string $date_created
   * @return BaseEntity
   */
  public function setDateCreated($date_created) {
    $this->date_created = $date_created;
    return $this;
  }

  /**
   * @return string
   */
  public function getDateUpdated() {
    return $this->date_updated;
  }

  /**
   * @param string $date_updated
   * @return BaseEntity
   */
  public function setDateUpdated($date_updated) {
    $this->date_updated = $date_updated;
    return $this;
  }

}