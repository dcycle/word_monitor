<?php

namespace Drupal\word_monitor_search;

use Drupal\Core\Cache\CacheTagsInvalidatorInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\State\State;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\system\SystemManager;

/**
 * WordMonitorSearch singleton. Use \Drupal::service('word_monitor_search').
 */
class WordMonitorSearch {

  use MessengerTrait;
  use StringTranslationTrait;

  /**
   * The injected renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * The injected cache_tags.invalidator.
   *
   * @var \Drupal\Core\Cache\CacheTagsInvalidatorInterface
   */
  protected $cacheTagsInvalidator;

  /**
   * The injected system.manager.
   *
   * @var \Drupal\system\SystemManager
   */
  protected $systemManager;

  /**
   * The state store.
   *
   * @var \Drupal\Core\State\State
   */
  protected $state;

  /**
   * The plugins.
   *
   * @var \Drupal\word_monitor_search\WordMonitorSearchPluginCollection
   */
  protected $plugins;

  /**
   * Constructs a new WordMonitor object.
   *
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   An injected renderer service.
   * @param \Drupal\Core\Cache\CacheTagsInvalidatorInterface $cache_tags_invalidator
   *   An injected cache_tags.invalidator service.
   * @param \Drupal\system\SystemManager $system_manager
   *   An injected system.manager service.
   * @param \Drupal\Core\State\State $state
   *   An injected state service.
   * @param \Drupal\word_monitor_search\WordMonitorSearchPluginCollection $plugins
   *   An injected word_monitor_search_plugin_collection service.
   */
  public function __construct(RendererInterface $renderer, CacheTagsInvalidatorInterface $cache_tags_invalidator, SystemManager $system_manager, State $state, WordMonitorSearchPluginCollection $plugins) {
    $this->renderer = $renderer;
    $this->cacheTagsInvalidator = $cache_tags_invalidator;
    $this->systemManager = $system_manager;
    $this->state = $state;
    $this->plugins = $plugins;
  }

  /**
   * Mockable wrapper around global $base_url.
   */
  public function baseUrl() : string {
    global $base_url;

    return $base_url;
  }

  /**
   * Get all WordMonitorPlugin plugins.
   *
   * See the included word_monitor_ignore module for an example of how to
   * create a Plugin.
   *
   * @return WordMonitorSearchPluginCollection
   *   All plugins.
   *
   * @throws \Exception
   */
  public function plugins() : WordMonitorSearchPluginCollection {
    return $this->plugins;
  }

  /**
   * {@inheritdoc}
   */
  public function selfTest() {
    $this->plugins()->selfTest();
  }

}
