<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 06.01.2017
 * Time: 19:35
 */

namespace Tallanto\Api\Entity;


class ClassIterator extends Iterator {

  /**
   * @return ClassIterator
   */
  public function reset() {
    /** @var ClassIterator $result */
    $result = parent::reset();
    return $result;
  }

  /**
   * @return ClassEntity
   */
  public function next() {
    return parent::next();
  }

}