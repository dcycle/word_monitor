<?php

namespace Drupal\word_monitor_search;

use Drupal\Component\Plugin\PluginBase;

/**
 * A base class to help developers implement WordMonitorPlugin objects.
 *
 * @see \Drupal\word_monitor_serach\Annotation\WordMonitorSearchPluginAnnotation
 * @see \Drupal\word_monitor_search\WordMonitorSearchPluginInterface
 */
abstract class WordMonitorSearchPluginBase extends PluginBase implements WordMonitorSearchPluginInterface {

  /**
   * A list of Drupal core Search Page types this applies to.
   *
   * @return array
   *   An array of Seach Page types.
   */
  abstract protected function appliesTo() : array;

  /**
   * Normalize a single result.
   *
   * @param array $result
   *   A result from a Drupal core Search Page, which can be different for
   *   different Search Page types.
   *
   * @return array
   *   A normalized result with the following keys:
   *   * id: the entity id.
   *   * entity: the entity.
   *   * entity_type: the entity type.
   *   * title: the entity's title
   *   * path: the entity's path.
   */
  abstract protected function convert(array $result) : array;

  /**
   * Normalize a result from a search page and add it to our results array.
   *
   * @param array $results
   *   Existing results, in a format where each element contains:
   *   * id: the entity id.
   *   * entity: the entity.
   *   * entity_type: the entity type.
   *   * title: the entity's title.
   *   * path: the entity's path.
   * @param string $search_page_id
   *   A search page id. In the core search module, there are two search pages,
   *   one for node and one for user, which return differently-structured data.
   *   Having this id will allow us to correctly interpret the $result
   *   paramter.
   * @param array $result
   *   The result array as returned from the search page with the ID in
   *   $search_page_id.
   */
  public function addResult(array &$results, string $search_page_id, array $result) {
    if (in_array($search_page_id, $this->appliesTo())) {
      try {
        $converted = $this->convert($result);
        $results[$converted['entity_type'] . ':' . $converted['id']] = $converted;
      }
      catch (\Exception $e) {
        watchdog_exception('word_monitor', $e);
      }
    }
  }

}
