<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 25.02.2017
 * Time: 14:26
 */

namespace Tallanto\Api;


interface ExpandableInterface {

  /**
   * Returns TRUE if entity is expanded, i.e. referenced objects are loaded.
   *
   * @return bool
   */
  public function isExpand();

  /**
   * Set expanded flag.
   *
   * @param bool $expanded
   * @return mixed
   */
  public function setExpand($expanded);


}