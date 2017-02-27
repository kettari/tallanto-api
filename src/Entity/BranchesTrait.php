<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 27.02.2017
 * Time: 16:53
 */

namespace Tallanto\Api\Entity;


trait BranchesTrait {

  /**
   * @var array
   */
  protected $branches;

  /**
   * @return array
   */
  public function getBranches() {
    return $this->branches;
  }

  /**
   * @param array $branches
   * @return mixed
   */
  public function setBranches($branches) {
    $this->branches = $branches;

    return $this;
  }

  /**
   * Sanitize branch and return array.
   *
   * @param $branch
   * @return array
   */
  private function sanitizeBranch($branch) {
    $branches = [];
    if (preg_match('/\^([a-zA-Z0-9_\-]+)\^/', $branch, $matches)) {
      for ($i = 1; $i < count($matches); $i++) {
        $branches[] = $matches[$i];
      }
    }

    return $branches;
  }
}