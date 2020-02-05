<?php

namespace Drupal\word_monitor_search;

use Symfony\Component\HttpFoundation\Request;

/**
 * Abstraction around a collection of plugins.
 */
class WordMonitorSearchPluginCollection implements WordMonitorSearchPluginInterface {

  /**
   * Mockable wrapper around \Drupal::service('plugin.manager.word_monitor').
   *
   * @return mixed
   *   The WordMonitorPluginManager service. We are not specifying its type
   *   here because during testing we want to mock pluginManager() without
   *   extending WordMonitorPluginManager; when we do, it works fine in
   *   PHPUnit directly. However when attempting to run within Drupal we
   *   get an unhelpful message as described in
   *   https://drupal.stackexchange.com/questions/252930. Therefore we simply
   *   use an anonymous class.
   *
   * @throws \Exception
   */
  public function pluginManager() {
    // PHPStan complains that dependency injection is better here than using
    // the \Drupal class, however dependency injection on custom classes is
    // rather complex, as described in
    // https://drupal.stackexchange.com/questions/195165/dependency-injection-in-a-custom-class,
    // and is of little value to us because or manner of mocking this in
    // tests is to mock the entire ::pluginManager() method, so our code
    // ends up testable even if we don't have dependency injection.
    // @codingStandardsIgnoreStart
    // @phpstan:ignoreError
    return \Drupal::service('plugin.manager.word_monitor_search');
    // @codingStandardsIgnoreEnd
  }

  /**
   * Get plugin objects.
   *
   * @param bool $reset
   *   Whether to re-fetch plugins; otherwise we use the static variable.
   *   This can be useful during testing.
   *
   * @return array
   *   Array of plugin objects.
   *
   * @throws \Exception
   */
  public function plugins(bool $reset = FALSE) : array {
    static $return = NULL;

    if ($return === NULL || $reset) {
      $return = [];
      foreach (array_keys($this->pluginDefinitions()) as $plugin_id) {
        $return[$plugin_id] = $this->pluginManager()->createInstance($plugin_id, ['of' => 'configuration values']);
      }
    }

    return $return;
  }

  /**
   * Get plugin definitions based on their annotations.
   *
   * @return array
   *   Array of plugin definitions.
   *
   * @throws \Exception
   */
  public function pluginDefinitions() : array {
    $return = $this->pluginManager()->getDefinitions();

    uasort($return, function (array $a, array $b) : int {
      if ($a['weight'] == $b['weight']) {
          return 0;
      }
      return ($a['weight'] < $b['weight']) ? -1 : 1;
    });

    return $return;
  }

  /**
   * Get an array of example URLs for usage.
   *
   * @param string $base_url
   *   The base URL to use for the examples.
   * @param string $token
   *   A token which should be used for the examples.
   *
   * @return array
   *   Array of example URLs for usage.
   *
   * @throws \Exception
   */
  public function exampleUrls(string $base_url, string $token) : array {
    $return = [];

    foreach ($this->pluginDefinitions() as $pluginDefinition) {
      foreach ($pluginDefinition['examples'] as $example) {
        $return[] = str_replace('[url]', $base_url, str_replace('[token]', $token, $example));
      }
    }

    return $return;
  }

  /**
   * {@inheritdoc}
   */
  public function alterResponse(Request $request, array $result, array &$response) {
    foreach ($this->plugins() as $plugin) {
      $plugin->alterResponse($request, $result, $response);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function addResult(array &$results, string $search_page_id, array $result) {
    foreach ($this->plugins() as $plugin) {
      $plugin->addResult($results, $search_page_id, $result);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function selfTest() {
    foreach ($this->plugins() as $plugin) {
      $plugin->selfTest();
    }
  }

}
