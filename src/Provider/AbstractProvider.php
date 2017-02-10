<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 10.02.2017
 * Time: 21:45
 */

namespace Tallanto\Api\Provider;


use Monolog\Logger;

class AbstractProvider {

  /**
   * @var Logger
   */
  protected $logger;

  /**
   * @var int
   */
  protected $page_number = 1;

  /**
   * @var int
   */
  protected $page_size = 20;

  /**
   * @var string
   */
  protected $query;

  /**
   * Set logger object.
   *
   * @param \Monolog\Logger $logger
   * @return AbstractProvider
   */
  public function setLogger($logger) {
    $this->logger = $logger;
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function getPageNumber() {
    return $this->page_number;
  }

  /**
   * @inheritdoc
   * @return AbstractProvider
   */
  public function setPageNumber($page) {
    $this->page_number = $page;
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function getPageSize() {
    return $this->page_size;
  }

  /**
   * @inheritdoc
   * @return AbstractProvider
   */
  public function setPageSize($size) {
    $this->page_size = $size;
    return $this;
  }

  /**
   * @inheritdoc
   */
  public function getQuery() {
    return $this->query;
  }

  /**
   * @inheritdoc
   * @return AbstractProvider
   */
  public function setQuery($query) {
    $this->query = $query;
    return $this;
  }
  
}