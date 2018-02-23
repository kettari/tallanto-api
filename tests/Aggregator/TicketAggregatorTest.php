<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 24.03.2017
 * Time: 18:58
 */

namespace Test\Tallanto\Api\Aggregator;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Tallanto\Api\Aggregator\TicketAggregator;
use Tallanto\Api\Entity\Ticket;
use Tallanto\Api\Provider\Http\ServiceProvider;

/**
 * Class TicketAggregatorTest
 *
 * @package Test\Tallanto\Api\Aggregator
 * @covers TicketAggregator
 */
class TicketAggregatorTest extends TestCase {

  public function testAddTickets() {
    $provider_mock = $this->createMock(ServiceProvider::class);
    $ticket1_mock = $this->createMock(Ticket::class);
    $ticket2_mock = $this->createMock(Ticket::class);
    $ticket3_mock = $this->createMock(Ticket::class);

    /** @var ServiceProvider $provider_mock */
    $aggregator = new TicketAggregator($provider_mock);
    $aggregator->append($ticket1_mock);
    $aggregator->append($ticket2_mock);
    $aggregator->append($ticket3_mock);

    $expected = 3;
    $actual = $aggregator->count();

    $this->assertEquals($expected, $actual, 'Expected 3 items, got: '.$actual);
  }

  public function testClearTickets() {
    $provider_mock = $this->createMock(ServiceProvider::class);
    $ticket1_mock = $this->createMock(Ticket::class);
    $ticket2_mock = $this->createMock(Ticket::class);
    $ticket3_mock = $this->createMock(Ticket::class);

    /** @var ServiceProvider $provider_mock */
    $aggregator = new TicketAggregator($provider_mock);
    $aggregator->append($ticket1_mock);
    $aggregator->append($ticket2_mock);
    $aggregator->append($ticket3_mock);
    $aggregator->clear();

    $expected = 0;
    $actual = $aggregator->count();

    $this->assertEquals($expected, $actual, 'Expected 0 items, got: '.$actual);
  }

  /**
   * @throws \ReflectionException
   */
  public function testBuildObject() {
    $provider_mock = $this->createMock(ServiceProvider::class);

    /** @var ServiceProvider $provider_mock */
    $buildObject = self::getMethod('buildObject');
    $aggregator = new TicketAggregator($provider_mock);

    $expected = Ticket::class;
    $actual = get_class($buildObject->invokeArgs($aggregator, [[]]));

    $this->assertEquals($expected, $actual);
  }

  /**
   * Changing protected method to public for tests.
   *
   * @see http://stackoverflow.com/questions/249664/best-practices-to-test-protected-methods-with-phpunit
   * @param $name
   * @return \ReflectionMethod
   * @throws \ReflectionException
   */
  protected static function getMethod($name) {
    $class = new ReflectionClass('Tallanto\Api\Aggregator\TicketAggregator');
    $method = $class->getMethod($name);
    $method->setAccessible(TRUE);

    return $method;
  }
}
