<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 05.01.2017
 * Time: 23:44
 */

namespace Tallanto\Api\Entity;


abstract class AbstractEntity {

  /**
   * AbstractEntity constructor.
   *
   * @param array $data
   */
  public function __construct($data = []) {
    foreach ($data as $key => $val) {
      if (property_exists($this, $key)) {
        $this->$key = $val;
      }
    }
  }

  /**
   * Serializes the object to an array including protected properties.
   *
   * @return array
   */
  public function toArray() {
    $vars = get_object_vars($this);
    foreach ($vars as $key => $var) {
      if ($var instanceof AbstractEntity) {
        $vars[$key] = $var->toArray();
      }
    }

    return $vars;
  }

}