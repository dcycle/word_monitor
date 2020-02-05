<?php

namespace Drupal\Tests\word_monitor_status_warning\Unit;

use Drupal\word_monitor_status_warning\WordMonitorStatusWarning;
use PHPUnit\Framework\TestCase;

/**
 * Test WordMonitorStatusWarning.
 *
 * @group word_monitor
 */
class WordMonitorStatusWarningTest extends TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    $object = $this->getMockBuilder(WordMonitorStatusWarning::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods(NULL)
      ->disableOriginalConstructor()
      ->getMock();

    $this->assertTrue(is_object($object));
  }

}
