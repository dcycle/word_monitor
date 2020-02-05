<?php

namespace Drupal\Tests\word_monitor\Unit;

use Drupal\word_monitor\WordMonitorPluginBase;
use PHPUnit\Framework\TestCase;

/**
 * Test WordMonitorPluginBase.
 *
 * @group word_monitor
 */
class WordMonitorPluginBaseTest extends TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    $object = $this->getMockBuilder(WordMonitorPluginBase::class)
      ->setMethods([])
      ->disableOriginalConstructor()
      ->getMockForAbstractClass();

    $this->assertTrue(is_object($object));
  }

}
