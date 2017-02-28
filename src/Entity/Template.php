<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 28.02.2017
 * Time: 15:44
 */

namespace Tallanto\Api\Entity;


class Template extends AbstractIdentifiableEntity {

  use BranchesTrait;

  /**
   * @var string
   */
  protected $name;

  /**
   * @var int
   */
  protected $duration;

  /**
   * @var int
   */
  protected $cost;

  /**
   * @var int
   */
  protected $single_visit_cost;

  /**
   * @var float
   */
  protected $num_visit;

  /**
   * @var bool
   */
  protected $active;

  public function __construct($data) {
    parent::__construct($data);

    // Correct boolean to look like boolean
    $this->active = $this->active ? TRUE : FALSE;

    // Sanitize branch
    $this->branches = $this->sanitizeBranch($this->branches);
  }

  /**
   * @return string
   */
  public function getName() {
    return $this->name;
  }

  /**
   * @param string $name
   * @return Template
   */
  public function setName($name) {
    $this->name = $name;

    return $this;
  }

  /**
   * @return int
   */
  public function getDuration() {
    return $this->duration;
  }

  /**
   * @param int $duration
   * @return Template
   */
  public function setDuration($duration) {
    $this->duration = $duration;

    return $this;
  }

  /**
   * @return int
   */
  public function getCost() {
    return $this->cost;
  }

  /**
   * @param int $cost
   * @return Template
   */
  public function setCost($cost) {
    $this->cost = $cost;

    return $this;
  }

  /**
   * @return int
   */
  public function getSingleVisitCost() {
    return $this->single_visit_cost;
  }

  /**
   * @param int $single_visit_cost
   * @return Template
   */
  public function setSingleVisitCost($single_visit_cost) {
    $this->single_visit_cost = $single_visit_cost;

    return $this;
  }

  /**
   * @return float
   */
  public function getNumVisit() {
    return $this->num_visit;
  }

  /**
   * @param float $num_visit
   * @return Template
   */
  public function setNumVisit($num_visit) {
    $this->num_visit = $num_visit;

    return $this;
  }

  /**
   * @return bool
   */
  public function isActive() {
    return $this->active;
  }

  /**
   * @param bool $active
   * @return Template
   */
  public function setActive($active) {
    $this->active = $active;

    return $this;
  }

}