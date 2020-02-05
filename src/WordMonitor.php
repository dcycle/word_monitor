<?php

namespace Drupal\word_monitor;

use Drupal\Core\State\State;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * WordMonitor singleton. Use \Drupal::service('word_monitor').
 */
class WordMonitor {

  use StringTranslationTrait;

  /**
   * The state store.
   *
   * @var \Drupal\Core\State\State
   */
  protected $state;

  /**
   * The plugins.
   *
   * @var \Drupal\word_monitor\WordMonitorPluginCollection
   */
  protected $plugins;

  /**
   * Constructs a new WordMonitor object.
   *
   * @param \Drupal\Core\State\State $state
   *   An injected state service.
   * @param \Drupal\word_monitor\WordMonitorPluginCollection $plugins
   *   An injected collection of plugins.
   */
  public function __construct(State $state, WordMonitorPluginCollection $plugins) {
    $this->state = $state;
    $this->plugins = $plugins;
  }

  /**
   * Find entities which contain a given word.
   *
   * @param string $word
   *   A word to find.
   *
   * @return array
   *   An array, where each item will contain the following keys:
   *   * id: the ID of the entity.
   *   * entity: the entity object.
   *   * entity_type: the entity type such as node or user.
   *   * title: the entity's title.
   *   * path: the entity's path.
   */
  public function find(string $word) : array {
    return $this->plugins()->find($word);
  }

  /**
   * Get all entities which contain a banned word.
   *
   * @return array
   *   All entities which contain a banned word, as an array, where each
   *   element contains the following keys:
   *   * word
   *   * link
   *   * title
   */
  public function findAll() : array {
    $return = [];
    foreach ($this->getWords() as $word) {
      $occurrences = $this->find($word);
      foreach ($occurrences as $occurrence) {
        $return[] = [
          'word' => $word,
          'link' => $occurrence['path'],
          'title' => $occurrence['title'],
        ];
      }
    }
    return $return;
  }

  /**
   * Get the list of banned words.
   *
   * @return array
   *   The list of banned words.
   */
  public function getWords() : array {
    return $this->state->get('word_monitor_words', []);
  }

  /**
   * Get all WordMonitorPlugin plugins.
   *
   * See the included word_monitor_ignore module for an example of how to
   * create a Plugin.
   *
   * @return WordMonitorPluginCollection
   *   All plugins.
   *
   * @throws \Exception
   */
  public function plugins() : WordMonitorPluginCollection {
    return $this->plugins;
  }

  /**
   * Run self-tests on the system.
   *
   * This is meant to be run from the command line like this:
   *
   * drush ev 'word_monitor()->selfTest();'
   *
   * The script will then exist with a non-zero error code in case of a
   * problem.
   */
  public function selfTest() {
    print_r('Starting self-test.' . PHP_EOL);
    $this->plugins()->selfTest();
    print_r('Self-test finished successfully.' . PHP_EOL);
  }

  /**
   * Delete existing words, replacing them with a new set of banned words.
   *
   * @param array $words
   *   A list of words to ban.
   */
  public function setWords(array $words) {
    $sanitized = [];

    array_walk($words, function ($item, $key) use (&$sanitized) {
      $item = trim($item);
      $item = str_replace('\r', '', $item);
      if ($item) {
        $sanitized[$item] = $item;
      }
    });

    return $this->state->set('word_monitor_words', $sanitized);
  }

}
