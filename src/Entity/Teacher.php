<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 07.01.2017
 * Time: 18:52
 */

namespace Tallanto\Api\Entity;


class Teacher extends User {

  /**
   * @var float
   */
  protected $profit;

  /**
   * @var string
   */
  protected $stake_id;

  /**
   * @var string
   */
  protected $stake_name;

  /**
   * @return float
   */
  public function getProfit() {
    return $this->profit;
  }

  /**
   * @param float $profit
   * @return Teacher
   */
  public function setProfit($profit) {
    $this->profit = $profit;

    return $this;
  }

  /**
   * @return string
   */
  public function getStakeId() {
    return $this->stake_id;
  }

  /**
   * @param string $stake_id
   * @return Teacher
   */
  public function setStakeId($stake_id) {
    $this->stake_id = $stake_id;

    return $this;
  }

  /**
   * @return string
   */
  public function getStakeName() {
    return $this->stake_name;
  }

  /**
   * @param string $stake_name
   * @return Teacher
   */
  public function setStakeName($stake_name) {
    $this->stake_name = $stake_name;

    return $this;
  }

}