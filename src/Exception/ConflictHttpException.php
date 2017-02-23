<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 23.02.2017
 * Time: 15:15
 */

namespace Tallanto\Api\Exception;


use Exception;

class ConflictHttpException extends HttpException {

  public function __construct($message = "", Exception $previous = NULL) {
    parent::__construct($message, 409, $previous);
  }

}