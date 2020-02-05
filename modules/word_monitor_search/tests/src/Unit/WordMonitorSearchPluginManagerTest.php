<?php

namespace Drupal\Tests\word_monitor_search\Unit;

use Drupal\word_monitor_search\WordMonitorSearchPluginManager;
use PHPUnit\Framework\TestCase;

/**
 * Test WordMonitorSearchPluginManager.
 *
 * @group word_monitor
 */
class WordMonitorSearchPluginManagerTest extends TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    $object = $this->getMockBuilder(WordMonitorSearchPluginManager::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods(NULL)
      ->disableOriginalConstructor()
      ->getMock();

    $this->assertTrue(is_object($object));
  }

}
