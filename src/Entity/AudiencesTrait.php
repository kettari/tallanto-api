<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 20.04.2017
 * Time: 15:03
 */

namespace Tallanto\Api\Entity;


trait AudiencesTrait
{
  /**
   * @var string
   */
  protected $audience;

  /**
   * @return string
   */
  public function getAudience() {
    return $this->audience;
  }

  /**
   * @param string $audience
   * @return mixed
   */
  public function setAudience($audience) {
    $this->audience = $audience;

    return $this;
  }

  /**
   * Sanitize audience and return string.
   *
   * @param $audience
   * @return string
   */
  protected function sanitizeAudience($audience) {
    $audiences = [];
    if (preg_match('/\^([a-zA-Z0-9_\-]+)\^/', $audience, $matches)) {
      for ($i = 1; $i < count($matches); $i++) {
        $audiences[] = $matches[$i];
      }
    }

    return count($audiences) ? reset($audiences) : $audience;
  }
}