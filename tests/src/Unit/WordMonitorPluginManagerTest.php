<?php

namespace Drupal\Tests\word_monitor\Unit;

use Drupal\word_monitor\WordMonitorPluginManager;
use PHPUnit\Framework\TestCase;

/**
 * Test WordMonitorPluginManager.
 *
 * @group word_monitor
 */
class WordMonitorPluginManagerTest extends TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    $object = $this->getMockBuilder(WordMonitorPluginManager::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods(NULL)
      ->disableOriginalConstructor()
      ->getMock();

    $this->assertTrue(is_object($object));
  }

}
