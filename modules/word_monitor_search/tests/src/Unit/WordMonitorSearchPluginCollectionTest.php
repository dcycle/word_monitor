<?php

namespace Drupal\Tests\word_monitor_search\Unit;

use Drupal\word_monitor_search\WordMonitorSearchPluginCollection;
use PHPUnit\Framework\TestCase;

/**
 * Test WordMonitorSearchPluginCollection.
 *
 * @group word_monitor
 */
class WordMonitorSearchPluginCollectionTest extends TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    $object = $this->getMockBuilder(WordMonitorSearchPluginCollection::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods(NULL)
      ->disableOriginalConstructor()
      ->getMock();

    $this->assertTrue(is_object($object));
  }

}
