<?php

namespace Drupal\Tests\word_monitor_search\Unit\Plugin\WordMonitorSearchPlugin;

use Drupal\word_monitor_search\Plugin\WordMonitorSearchPlugin\WordMonitorSearchNode;
use PHPUnit\Framework\TestCase;

/**
 * Test WordMonitorSearchNode.
 *
 * @group word_monitor
 */
class WordMonitorSearchNodeTest extends TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    $object = $this->getMockBuilder(WordMonitorSearchNode::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods(NULL)
      ->disableOriginalConstructor()
      ->getMock();

    $this->assertTrue(is_object($object));
  }

}
