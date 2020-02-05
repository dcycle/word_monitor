<?php

namespace Drupal\Tests\word_monitor_search\Unit;

use Drupal\word_monitor_search\WordMonitorSearchPluginBase;
use PHPUnit\Framework\TestCase;

/**
 * Test WordMonitorSearchPluginBase.
 *
 * @group word_monitor
 */
class WordMonitorSearchPluginBaseTest extends TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    $object = $this->getMockBuilder(WordMonitorSearchPluginBase::class)
      ->setMethods([])
      ->disableOriginalConstructor()
      ->getMockForAbstractClass();

    $this->assertTrue(is_object($object));
  }

}
