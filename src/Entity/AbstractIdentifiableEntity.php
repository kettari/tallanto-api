<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 27.02.2017
 * Time: 16:59
 */

namespace Tallanto\Api\Entity;


abstract class AbstractIdentifiableEntity extends AbstractEntity {

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
   * @return string
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @param string $id
   * @return AbstractEntity
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
   * @return AbstractEntity
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
   * @return AbstractEntity
   */
  public function setDateUpdated($date_updated) {
    $this->date_updated = $date_updated;
    return $this;
  }

}