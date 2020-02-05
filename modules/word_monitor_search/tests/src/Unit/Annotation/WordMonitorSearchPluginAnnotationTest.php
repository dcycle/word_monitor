<?php

namespace Drupal\Tests\word_monitor_search\Unit\Annotation;

use Drupal\word_monitor_search\Annotation\WordMonitorSearchPluginAnnotation;
use PHPUnit\Framework\TestCase;

/**
 * Test WordMonitorSearch.
 *
 * @group word_monitor
 */
class WordMonitorSearchPluginAnnotationTest extends TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    $object = $this->getMockBuilder(WordMonitorSearchPluginAnnotation::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods(NULL)
      ->disableOriginalConstructor()
      ->getMock();

    $this->assertTrue(is_object($object));
  }

}
