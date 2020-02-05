<?php

namespace Drupal\word_monitor_search\Plugin\WordMonitorSearchPlugin;

use Drupal\word_monitor_search\WordMonitorSearchPluginBase;

/**
 * Converts node search results.
 *
 * @WordMonitorSearchPluginAnnotation(
 *   id = "word_monitor_search_node",
 *   weight = 0,
 *   description = @Translation("Converts node search results."),
 * )
 */
class WordMonitorSearchNode extends WordMonitorSearchPluginBase {

  /**
   * {@inheritdoc}
   */
  protected function appliesTo() : array {
    return [
      'node_search',
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function convert(array $result) : array {
    $id = $result['#result']['node']->id();
    return [
      'id' => $id,
      'entity' => $result['#result']['node'],
      'entity_type' => 'node',
      'title' => $result['#result']['node']->getTitle(),
      'path' => '/node/' . $id,
    ];
  }

}
