<?php

namespace Drupal\word_monitor_status_warning;

use Drupal\Core\Link;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Drupal\word_monitor\WordMonitor;

/**
 * Singleton accessed by \Drupal::service('word_monitor_status_warning').
 */
class WordMonitorStatusWarning {

  use StringTranslationTrait;

  /**
   * The inject word_monitor service.
   *
   * @var \Drupal\word_monitor\WordMonitor
   */
  protected $wordMonitor;

  /**
   * Constructs a new WordMonitorStatusWarning object.
   *
   * @param \Drupal\word_monitor\WordMonitor $word_monitor
   *   An injected word_monitor service.
   */
  public function __construct(WordMonitor $word_monitor) {
    $this->wordMonitor = $word_monitor;
  }

  /**
   * Testable implementation of hook_requirements().
   */
  public function hookRequirements(string $phase) : array {
    $return = [];

    $url = Url::fromRoute('word_monitor.settings');
    $project_link = Link::fromTextAndUrl($this->t('the project settings page'), $url);
    $project_link = $project_link->toRenderable();

    $count = $this->wordMonitor->findAll();
    if ($phase == 'runtime') {
      $return['word_monitor_status_warning'] = [
        'title' => $this->t('Monitor for banned words on your site'),
        'value' => $count,
        'description' => $this->t('You can define banned words and see where they are on the site at @l', [
          '@l' => render($project_link),
        ]),
        'severity' => $count ? REQUIREMENT_WARNING : REQUIREMENT_OK,
      ];
    }

    return $return;
  }

}
