<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 10.02.2017
 * Time: 21:45
 */

namespace Tallanto\Api\Provider;


use DateTime;
use Psr\Log\LoggerInterface;

class AbstractProvider
{

  /**
   * @var  LoggerInterface
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
   * @var string
   */
  protected $parameter;

  /**
   * @var \DateTime
   */
  protected $if_modified_since;

  /**
   * Set logger object.
   *
   * @param  LoggerInterface $logger
   * @return AbstractProvider
   */
  public function setLogger($logger)
  {
    $this->logger = $logger;

    return $this;
  }

  /**
   * @inheritdoc
   */
  public function getPageNumber()
  {
    return $this->page_number;
  }

  /**
   * @inheritdoc
   * @return AbstractProvider
   */
  public function setPageNumber($page)
  {
    $this->page_number = $page;

    return $this;
  }

  /**
   * @inheritdoc
   */
  public function getPageSize()
  {
    return $this->page_size;
  }

  /**
   * @inheritdoc
   * @return AbstractProvider
   */
  public function setPageSize($size)
  {
    $this->page_size = $size;

    return $this;
  }

  /**
   * @inheritdoc
   */
  public function getQuery()
  {
    return $this->query;
  }

  /**
   * @inheritdoc
   * @return AbstractProvider
   */
  public function setQuery($query)
  {
    $this->query = $query;

    return $this;
  }

  /**
   * @return string
   */
  public function getParameter()
  {
    return $this->parameter;
  }

  /**
   * @param string $parameter
   * @return AbstractProvider
   */
  public function setParameter(string $parameter)
  {
    $this->parameter = $parameter;

    return $this;
  }

  /**
   * @return DateTime
   */
  public function getIfModifiedSince()
  {
    return $this->if_modified_since;
  }

  /**
   * @param DateTime $if_modified_since
   * @return AbstractProvider
   */
  public function setIfModifiedSince($if_modified_since)
  {
    $this->if_modified_since = $if_modified_since;

    return $this;
  }

}