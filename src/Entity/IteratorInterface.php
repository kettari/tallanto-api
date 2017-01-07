<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 06.01.2017
 * Time: 16:45
 */

namespace Tallanto\Api\Entity;


interface IteratorInterface {

  /**
   * Resets current item pointer
   *
   * @return IteratorInterface
   */
  public function reset();

  /**
   * @return boolean if there is next array item
   */
  public function hasNext();

  /**
   * @return mixed next array item
   */
  public function next();

  /**
   * Removes current item
   *
   * @return void
   */
  public function remove();

  /**
   * @return integer
   */
  public function count();

}