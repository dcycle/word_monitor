<?php

namespace Drupal\word_monitor_search;

/**
 * An interface for all WordMonitorPlugin type plugins.
 */
interface WordMonitorSearchPluginInterface {

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
  public function addResult(array &$results, string $search_page_id, array $result);

}
