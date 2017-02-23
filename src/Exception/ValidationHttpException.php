<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 23.02.2017
 * Time: 18:59
 */

namespace Tallanto\Api\Exception;


use Exception;

class ValidationHttpException extends HttpException {

  public function __construct($message = "", Exception $previous = NULL) {
    parent::__construct($message, 400, $previous);
  }

}