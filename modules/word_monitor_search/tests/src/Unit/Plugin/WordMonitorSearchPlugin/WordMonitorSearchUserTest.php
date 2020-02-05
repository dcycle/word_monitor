<?php

namespace Drupal\Tests\word_monitor_search\Unit\Plugin\WordMonitorSearchPlugin;

use Drupal\word_monitor_search\Plugin\WordMonitorSearchPlugin\WordMonitorSearchUser;
use PHPUnit\Framework\TestCase;

/**
 * Test WordMonitorSearchUser.
 *
 * @group word_monitor
 */
class WordMonitorSearchUserTest extends TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    $object = $this->getMockBuilder(WordMonitorSearchUser::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods(NULL)
      ->disableOriginalConstructor()
      ->getMock();

    $this->assertTrue(is_object($object));
  }

}
