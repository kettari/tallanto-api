<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 06.01.2017
 * Time: 19:29
 */

namespace Tallanto\Api\Entity;


use Exception;

abstract class Iterator implements IteratorInterface {

  /**
   * @var array
   */
  protected $items;

  /**
   * @var int
   */
  protected $position = 0;

  /**
   * Iterator constructor.
   *
   * @param array $items
   */
  public function __construct(array $items) {
    $this->items = $items;
  }

  /**
   * Resets current item pointer
   *
   * @return Iterator
   */
  public function reset() {
    $this->position = 0;

    return $this;
  }

  /**
   * @return boolean if there is next array item
   */
  public function hasNext() {
    if ($this->position >= count($this->items) || count($this->items) == 0) {
      return FALSE;
    }
    else {
      return TRUE;
    }
  }

  /**
   * @return mixed|null
   */
  public function next() {
    if (isset($this->items[$this->position])) {
      $item = $this->items[$this->position];
      $this->position++;

      return $item;
    }

    return NULL;
  }

  /**
   * Removes current item
   *
   * @throws \Exception
   * @return Iterator
   */
  public function remove() {
    if (count($this->items) == 0) {
      throw new Exception('Unable to remove item: array is empty');
    }
    if ($this->position <= 0) {
      throw new Exception('Unable to remove item: call next() method first');
    }
    if (!is_null($this->items[$this->position - 1])) {
      array_splice($this->items, ($this->position - 1), 1);
    }

    return $this;
  }

  /**
   * @return int
   */
  public function count() {
    return count($this->items);
  }


}