<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 20.04.2017
 * Time: 15:01
 */

namespace Tallanto\Api\Entity;


use Tallanto\Api\ExpandableInterface;
use Tallanto\Api\ExpandableTrait;

class Payroll extends AbstractIdentifiableEntity  implements ExpandableInterface
{
  use ExpandableTrait, BranchesTrait, AudiencesTrait;

  /**
   * @var string
   */
  protected $name;

  /**
   * @var string
   */
  protected $description;

  /**
   * @var float
   */
  protected $cost;

  /**
   * @var string
   */
  protected $direction;

  /**
   * @var string
   */
  protected $date_payment;

  /**
   * @var string
   */
  protected $employee_id;

  /**
   * @var string
   */
  protected $class_id;

  /**
   * @var ClassEntity
   */
  protected $class;

  /**
   * Payroll constructor.
   *
   * @param array $data
   */
  public function __construct($data)
  {
    parent::__construct($data);

    // Sanitize branch
    $this->branches = $this->sanitizeBranch($this->branches);
    // Sanitize audience
    $this->audience = $this->sanitizeAudience($this->audience);

    // Build Class objects
    if (isset($data['class']) && !is_null($data['class'])) {
      $this->class = new ClassEntity($data['class']);
      // Expanded variables are provided, set the flag
      $this->setExpand(true);
    }
  }

  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * @param string $name
   * @return Payroll
   */
  public function setName(string $name): Payroll
  {
    $this->name = $name;

    return $this;
  }

  /**
   * @return string
   */
  public function getDescription()
  {
    return $this->description;
  }

  /**
   * @param string $description
   * @return Payroll
   */
  public function setDescription(string $description): Payroll
  {
    $this->description = $description;

    return $this;
  }

  /**
   * @return float
   */
  public function getCost()
  {
    return $this->cost;
  }

  /**
   * @param float $cost
   * @return Payroll
   */
  public function setCost(float $cost): Payroll
  {
    $this->cost = $cost;

    return $this;
  }

  /**
   * @return string
   */
  public function getDirection()
  {
    return $this->direction;
  }

  /**
   * @param string $direction
   * @return Payroll
   */
  public function setDirection(string $direction): Payroll
  {
    $this->direction = $direction;

    return $this;
  }

  /**
   * @return string
   */
  public function getDatePayment()
  {
    return $this->date_payment;
  }

  /**
   * @param string $date_payment
   * @return Payroll
   */
  public function setDatePayment(string $date_payment): Payroll
  {
    $this->date_payment = $date_payment;

    return $this;
  }

  /**
   * @return string
   */
  public function getEmployeeId()
  {
    return $this->employee_id;
  }

  /**
   * @param string $employee_id
   * @return Payroll
   */
  public function setEmployeeId(string $employee_id): Payroll
  {
    $this->employee_id = $employee_id;

    return $this;
  }

  /**
   * @return string
   */
  public function getClassId()
  {
    return $this->class_id;
  }

  /**
   * @param string $class_id
   * @return Payroll
   */
  public function setClassId(string $class_id): Payroll
  {
    $this->class_id = $class_id;

    return $this;
  }

  /**
   * @return ClassEntity
   */
  public function getClass()
  {
    return $this->class;
  }

  /**
   * @param ClassEntity $class
   * @return Payroll
   */
  public function setClass(ClassEntity $class): Payroll
  {
    $this->class = $class;

    return $this;
  }

}