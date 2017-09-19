<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 27.02.2017
 * Time: 16:53
 */

namespace Tallanto\Api\Entity;


trait BranchesTrait
{

  /**
   * @var array
   */
  protected $branches;

  /**
   * @return array
   */
  public function getBranches()
  {
    return $this->branches;
  }

  /**
   * @param array $branches
   * @return mixed
   */
  public function setBranches($branches)
  {
    $this->branches = $branches;

    return $this;
  }

  /**
   * Sanitize branch and return array.
   *
   * @param $branch
   * @return array
   */
  private function sanitizeBranch($branch)
  {
    if (is_array($branch)) {
      return $branch;
    }
    $branches = [];
    if (preg_match_all('/\^([a-z0-9\_\-]+)\^/i', $branch, $matches)) {
      if (isset($matches[1])) {
        foreach ($matches[1] as $branch) {
          $branches[] = $branch;
        }
      }
    }

    return $branches;
  }
}