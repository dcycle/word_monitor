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
   * Test for countToRequirementsArray().
   *
   * @param string $message
   *   The test message.
   * @param array $errors
   *   Array of errors.
   * @param array $expected
   *   Expected result.
   *
   * @cover ::countToRequirementsArray
   * @dataProvider providerCountToRequirementsArray
   */
  public function testCountToRequirementsArray(string $message, array $errors, array $expected) {
    $object = $this->getMockBuilder(WordMonitorStatusWarning::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods([
        't',
      ])
      ->disableOriginalConstructor()
      ->getMock();

    $object->method('t')
      ->will($this->returnCallback(function ($s, array $a = []) {
        return serialize([$s, $a]);
      }));

    $output = $object->countToRequirementsArray($errors, 'some project link', 'warning', 'ok');

    if ($output != $expected) {
      print_r([
        'message' => $message,
        'output' => $output,
        'expected' => $expected,
      ]);
    }

    $this->assertTrue($output == $expected, $message);
  }

  /**
   * Provider for testCountToRequirementsArray().
   */
  public function providerCountToRequirementsArray() {
    return [
      [
        'message' => 'No errors',
        'errors' => [],
        'expected' => [
          'title' => serialize(['Monitor for banned words on your site', []]),
          'value' => serialize(['@c banned words found', ['@c' => 0]]),
          'description' => serialize(['You can define banned words and see where they are on the site at @l', ['@l' => 'some project link']]),
          'severity' => 'ok',
        ],
      ],
      [
        'message' => 'One error',
        'errors' => [
          'whatever',
        ],
        'expected' => [
          'title' => serialize(['Monitor for banned words on your site', []]),
          'value' => serialize(['@c banned words found', ['@c' => 1]]),
          'description' => serialize(['You can define banned words and see where they are on the site at @l', ['@l' => 'some project link']]),
          'severity' => 'warning',
        ],
      ],
      [
        'message' => 'Five errors',
        'errors' => [
          'whatever',
          'whatever',
          'whatever',
          'whatever',
          'whatever',
        ],
        'expected' => [
          'title' => serialize(['Monitor for banned words on your site', []]),
          'value' => serialize(['@c banned words found', ['@c' => 5]]),
          'description' => serialize(['You can define banned words and see where they are on the site at @l', ['@l' => 'some project link']]),
          'severity' => 'warning',
        ],
      ],
    ];
  }

}
