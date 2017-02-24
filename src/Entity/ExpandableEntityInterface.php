<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 24.02.2017
 * Time: 18:03
 */

namespace Entity;


interface ExpandableEntityInterface {

  /**
   * Returns TRUE if entity is expanded, i.e. referenced objects are loaded.
   *
   * @return bool
   */
  public function isExpanded();

  /**
   * Set expanded flag.
   *
   * @param bool $expanded
   * @return mixed
   */
  public function setExpanded($expanded);

}