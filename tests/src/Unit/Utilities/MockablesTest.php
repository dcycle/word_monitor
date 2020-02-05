<?php

namespace Drupal\Tests\word_monitor\Unit\Utilities;

use Drupal\word_monitor\Utilities\Mockables;
use PHPUnit\Framework\TestCase;

// @codingStandardsIgnoreStart
class DummyMockablesObject {
  use Mockables;

}
// @codingStandardsIgnoreEnd

/**
 * Test Mockables.
 *
 * @group word_monitor
 */
class MockablesTest extends TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    $object = new DummyMockablesObject();

    $this->assertTrue(is_object($object));
  }

}
