<?php
/**
 * Created by PhpStorm.
 * User: Ант
 * Date: 18.01.2017
 * Time: 18:43
 */

namespace Tallanto\Api\Aggregator;

use ArrayObject;
use Tallanto\Api\Provider\ProviderInterface;

abstract class AbstractAggregator extends ArrayObject {

  /**
   * Data provider
   *
   * @var ProviderInterface
   */
  protected $provider;

  /**
   * AbstractAggregator constructor.
   *
   * @param \Tallanto\Api\Provider\ProviderInterface $provider
   */
  public function __construct(ProviderInterface $provider) {
    $this->provider = $provider;
  }


  /**
   * Clears items in the internal collection. Does not change state of items
   * in the original storage.
   *
   * @return void
   */
  public function clear() {
    $iterator = $this->getIterator();
    foreach ($iterator as $key => $item) {
      $iterator->offsetUnset($key);
    }
  }

}