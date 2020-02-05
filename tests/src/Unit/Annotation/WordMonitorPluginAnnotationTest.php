<?php

namespace Drupal\Tests\word_monitor\Unit\Annotation;

use Drupal\word_monitor\Annotation\WordMonitorPluginAnnotation;
use PHPUnit\Framework\TestCase;

/**
 * Test WordMonitorPluginAnnotation.
 *
 * @group word_monitor
 */
class WordMonitorPluginAnnotationTest extends TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    $object = $this->getMockBuilder(WordMonitorPluginAnnotation::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods(NULL)
      ->disableOriginalConstructor()
      ->getMock();

    $this->assertTrue(is_object($object));
  }

}
