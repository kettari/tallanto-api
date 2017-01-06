<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 05.01.2017
 * Time: 23:44
 */

namespace Tallanto\Api\Entity;


class BaseEntity {

  /**
   * BaseEntity constructor.
   *
   * @param string $class Class name to check for "magic" properties
   * @param $data
   */
  public function __construct($class, $data) {
    foreach ($data as $key => $val) {
      if (property_exists(__CLASS__, $key)) {
        $this->$key = $val;
      }
    }
  }

}