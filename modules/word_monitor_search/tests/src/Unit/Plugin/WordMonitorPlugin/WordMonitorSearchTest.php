<?php

namespace Drupal\Tests\word_monitor_search\Unit\Plugin\WordMonitorPlugin;

use Drupal\word_monitor_search\Plugin\WordMonitorPlugin\WordMonitorSearch;
use PHPUnit\Framework\TestCase;

/**
 * Test WordMonitorSearch.
 *
 * @group word_monitor
 */
class WordMonitorSearchTest extends TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    $object = $this->getMockBuilder(WordMonitorSearch::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods(NULL)
      ->disableOriginalConstructor()
      ->getMock();

    $this->assertTrue(is_object($object));
  }

}
