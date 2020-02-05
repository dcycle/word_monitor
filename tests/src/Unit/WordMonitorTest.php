<?php

namespace Drupal\Tests\word_monitor\Unit;

use Drupal\word_monitor\WordMonitor;
use PHPUnit\Framework\TestCase;

/**
 * Test WordMonitor.
 *
 * @group word_monitor
 */
class WordMonitorTest extends TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    $object = $this->getMockBuilder(WordMonitor::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods(NULL)
      ->disableOriginalConstructor()
      ->getMock();

    $this->assertTrue(is_object($object));
  }

}
