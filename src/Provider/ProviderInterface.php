<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 10.02.2017
 * Time: 19:38
 */

namespace Tallanto\Api\Provider;


interface ProviderInterface {

  /**
   * Fetches (loads) data from the upstream using page number, page size and possible ID
   * and query values.
   *
   * Returns array if everything is OK.
   *
   * @return array
   */
  function fetch();

  /**
   * Returns total number of records that fulfil the criteria.
   *
   * @return integer
   */
  function totalCount();

  /**
   * Returns current page number.
   *
   * @return mixed
   */
  public function getPageNumber();

  /**
   * Sets page number.
   *
   * @param integer $page
   * @return ProviderInterface
   */
  public function setPageNumber($page);

  /**
   * Returns current page size value.
   *
   * @return integer
   */
  public function getPageSize();

  /**
   * Sets page size.
   *
   * @param integer $size
   * @return ProviderInterface
   */
  public function setPageSize($size);

  /**
   * Returns current query (filter).
   *
   * @return string
   */
  public function getQuery();

  /**
   * Sets query (filter).
   *
   * @param string $query
   * @return ProviderInterface
   */
  public function setQuery($query);

}