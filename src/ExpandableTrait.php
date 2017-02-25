<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 25.02.2017
 * Time: 15:26
 */

namespace Tallanto\Api;


trait ExpandableTrait {

  /**
   * @var bool
   */
  protected $expand = FALSE;

  /**
   * Returns TRUE if entity is expanded, i.e. referenced objects are loaded.
   *
   * @return bool
   */
  public function isExpand() {
    return $this->expand;
  }

  /**
   * Set expanded flag.
   *
   * @param bool $expand
   * @return mixed
   */
  public function setExpand($expand) {
    $this->expand = $expand;

    return $this;
  }

}