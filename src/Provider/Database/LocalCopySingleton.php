<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 12.04.2017
 * Time: 2:37
 */

namespace Tallanto\Api\Provider\Database;


class LocalCopySingleton
{
  /**
   * @var null
   */
  private static $instance = null;

  /**
   * @var array
   */
  private $cache = [];

  /**
   * @inheritDoc
   */
  private function __construct()
  {
  }

  /**
   * @inheritDoc
   */
  private function __clone()
  {
  }

  /**
   * Return instance of this singleton
   *
   * @return \Tallanto\Api\Provider\Database\LocalCopySingleton
   */
  public static function getInstance()
  {
    if (is_null(self::$instance)) {
      self::$instance = new LocalCopySingleton();
    }

    return self::$instance;
  }

  /**
   * Sets cache item.
   *
   * @param string $category
   * @param string $key
   * @param mixed $value
   */
  public function setCache($category, $key, $value)
  {
    $this->cache[$category][$key] = $value;
  }

  /**
   * Returns cache item.
   *
   * @param string $category
   * @param string $key
   * @return mixed|null
   */
  public function getCache($category, $key)
  {
    return isset($this->cache[$category][$key]) ? $this->cache[$category][$key] : null;
  }

}