<?php

namespace Drupal\word_monitor_search;

use Drupal\Component\Utility\Crypt;
use Drupal\Core\Cache\CacheTagsInvalidatorInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\Render\RenderContext;
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
   * Get example URLs including examples from plugins.
   *
   * @param bool $obfuscate
   *   Whether or not to obfuscate the security token in the examples.
   *
   * @return array
   *   Array of example URLs for usage of this system.
   */
  public function exampleUrls(bool $obfuscate) : array {
    $base_url = $this->baseUrl();
    $token = $this->token($obfuscate);

    return array_merge([
      $base_url . '/admin/reports/status/expose/' . $token,
    ], $this->plugins()->exampleUrls($base_url, $token));
  }

  /**
   * Generate a random token.
   *
   * @return string
   *   A random token.
   */
  public function generateToken() : string {
    return Crypt::hashBase64(random_bytes(128));
  }

  /**
   * Testable implementation of hook_requirements().
   */
  public function hookRequirements(string $phase) : array {
    if ($phase == 'runtime') {
      $this->messenger()->addMessage($this->instructions(TRUE) . ' ' . $this->t('To see the actual token value (instaed of ******), which we are not showing here due to security considerations, use drush ev "word_monitor_instructions()"'));
    }
    return [];
  }

  /**
   * Get a string with instructions how to use this system, with examples.
   *
   * @param bool $obfuscate
   *   If TRUE, obfucase the token in the output.
   *
   * @return string
   *   Instructions on how to use this system.
   *
   * @throws \Exception
   */
  public function instructions(bool $obfuscate = FALSE) : string {
    return $this->t('You can get the overall status in JSON format using, for example, @urls.', [
      '@urls' => implode(', ', $this->exampleUrls($obfuscate)),
    ]);
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
   * Get the raw data from Drupal.
   *
   * @return array
   *   The raw data of Drupal requirements.
   *
   * @throws \Exception
   */
  public function rawData() : array {
    $return = [];

    // Certain items in the requirements list might contain leaked caching
    // information. For example, some modules may put a node URL within
    // the description of one of the requirements defined with
    // hook_requirements. If we do not use our own render context, and then
    // try to cache our response, Drupal will complain that
    // "leaked metadata was detected" and refuse to render the resulting
    // JSON.
    // See https://blog.dcycle.com/blog/2018-01-24.
    $system_manager = $this->systemManager;
    // @codingStandardsIgnoreStart
    $this->renderer->executeInRenderContext(new RenderContext(), function () use (&$return, $system_manager) {
      $return = $system_manager->listRequirements();
    });
    // @codingStandardsIgnoreEnd

    return $return;
  }

  /**
   * Given raw data, return either "ok", or "issues found; please check".
   *
   * Developers can override this using the plugin system.
   *
   * @param array $raw
   *   The raw data.
   *
   * @return string
   *   "ok", or "issues found; please check".
   */
  public function rawDataToStatus(array $raw) : string {
    $return = 'ok';

    foreach ($raw as $line) {
      if (isset($line['severity']) && $line['severity'] > 0) {
        $return = 'issues found; please check';
        break;
      }
    }

    return $return;
  }

  /**
   * {@inheritdoc}
   */
  public function selfTest() {
    $this->plugins()->selfTest();
  }

  /**
   * Get the token, creating it if it does not exist.
   *
   * @param bool $obfuscate
   *   If TRUE, return an obfuscated (*****) version of the token.
   * @param bool $reset
   *   Whether or not reset the token.
   *
   * @return string
   *   The existing or newly-created token, or an obfuscated version thereof.
   */
  public function token(bool $obfuscate = FALSE, bool $reset = FALSE) : string {
    $candidate = $this->state->get('word_monitor_token', '');
    if (!$candidate || $reset) {
      $candidate = $this->generateToken();
      $this->cacheTagsInvalidator
        ->invalidateTags([
          'expose-status-security-token-has-changed',
        ]);
      $this->state->set('word_monitor_token', $candidate);
    }
    return $obfuscate ? '*****' : $candidate;
  }

}
