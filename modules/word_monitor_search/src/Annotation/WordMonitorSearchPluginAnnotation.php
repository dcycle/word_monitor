<?php

namespace Drupal\word_monitor_search\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines an Word Monitor Search Plugin annotation object.
 *
 * See the plugin_type_example module of the examples module for how this works.
 *
 * @see http://drupal.org/project/examples
 * @see \Drupal\word_monitor_search\WordMonitorSearchPluginManager
 * @see plugin_api
 *
 * @Annotation
 */
class WordMonitorSearchPluginAnnotation extends Plugin {

  /**
   * A brief, human readable, description of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $description;

  /**
   * How this modifier should be ordered.
   *
   * @var float
   */
  public $weight;

}
