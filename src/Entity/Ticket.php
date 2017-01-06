<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 06.01.2017
 * Time: 16:11
 */

namespace Tallanto\Api\Entity;


class Ticket extends BaseEntity {

  /**
   * Visit constructor.
   *
   * @param $data
   */
  public function __construct($data) {
    parent::__construct(__CLASS__, $data);
  }

}