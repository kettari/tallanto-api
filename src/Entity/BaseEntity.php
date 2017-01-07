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
   * @param $data
   */
  public function __construct($data) {
    foreach ($data as $key => $val) {
      if (property_exists($this, $key)) {
        $this->$key = $val;
      }
    }
  }

}