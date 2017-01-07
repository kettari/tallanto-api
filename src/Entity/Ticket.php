<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 06.01.2017
 * Time: 16:11
 */

namespace Tallanto\Api\Entity;


class Ticket extends BaseEntity {

  /**
   * @var string
   */
  protected $id;

  /**
   * @var string
   */
  protected $name;

  /**
   * @var string
   */
  protected $start_date;

  /**
   * @var string
   */
  protected $finish_date;

  /**
   * @var string
   */
  protected $template_name;

  /**
   * @var string
   */
  protected $template_id;

  /**
   * @var Contact
   */
  protected $owner;

  /**
   * @var integer
   */
  protected $cost;

  /**
   * @return string
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @return string
   */
  public function getName() {
    return $this->name;
  }

  /**
   * @return string
   */
  public function getStartDate() {
    return $this->start_date;
  }

  /**
   * @return string
   */
  public function getFinishDate() {
    return $this->finish_date;
  }

  /**
   * @return string
   */
  public function getTemplateName() {
    return $this->template_name;
  }

  /**
   * @return string
   */
  public function getTemplateId() {
    return $this->template_id;
  }

  /**
   * @return \Tallanto\Api\Entity\Contact
   */
  public function getOwner() {
    return $this->owner;
  }

  /**
   * @return integer
   */
  public function getCost() {
    return $this->cost;
  }

}