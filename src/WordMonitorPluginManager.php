<?php

namespace Drupal\word_monitor;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\word_monitor\Annotation\WordMonitorPluginAnnotation;

/**
 * A plugin manager for WordMonitorPlugin plugins.
 *
 * The WordMonitorPluginManager class extends the DefaultPluginManager to
 * provide a way to manage WordMonitorPlugin plugins. A plugin manager defines
 * a new plugin type and how instances of any plugin of that type will be
 * discovered, instantiated and more.
 *
 * Using the DefaultPluginManager as a starting point sets up our
 * WordMonitorPlugin plugin type to use annotated discovery.
 *
 * The plugin manager is also declared as a service in
 * word_monitor.services.yml so that it can be easily accessed and used
 * anytime we need to work with WordMonitorPlugin plugins.
 *
 * This is based on code from the Examples module which can be found at
 * https://github.com/drupalprojects/examples/blob/8.x-1.x/plugin_type_example/src/SandwichPluginManager.php
 */
// See https://github.com/mglaman/phpstan-drupal/issues/113
// @codingStandardsIgnoreStart
class WordMonitorPluginManager extends DefaultPluginManager {
// @codingStandardsIgnoreEnd

  /**
   * Creates the discovery object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  // See https://github.com/mglaman/phpstan-drupal/issues/112
  // @codingStandardsIgnoreStart
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
  // @codingStandardsIgnoreEnd
    // We replace the $subdir parameter with our own value.
    // This tells the plugin manager to look for plugins in the
    // 'src/Plugin/WordMonitorPlugin' subdirectory of any enabled modules.
    // This also serves to define the PSR-4 subnamespace in which plugins will
    // live. Modules can put a plugin class in their own namespace such as
    // Drupal\{module_name}\Plugin\WordMonitorPlugin\MyPlugin.
    $subdir = 'Plugin/WordMonitorPlugin';

    // The name of the interface that plugins should adhere to. Drupal will
    // enforce this as a requirement. If a plugin does not implement this
    // interface, Drupal will throw an error.
    $plugin_interface = WordMonitorPluginInterface::class;

    // The name of the annotation class that contains the plugin definition.
    $plugin_definition_annotation_name = WordMonitorPluginAnnotation::class;

    parent::__construct($subdir, $namespaces, $module_handler, $plugin_interface, $plugin_definition_annotation_name);

    // This sets the caching method for our plugin definitions. Plugin
    // definitions are discovered by examining the $subdir defined above, for
    // any classes with an $plugin_definition_annotation_name. The annotations
    // are read, and then the resulting data is cached using the provided cache
    // backend. For our WordMonitorPlugin plugin type, we've specified the
    // "@cache.default" service be used in the plugin_type_example.services.yml
    // file (*) but see https://www.drupal.org/project/examples/issues/3109867.
    // The second argument is a cache key prefix. Out of the box Drupal
    // with the default cache backend setup will store our plugin definition in
    // the cache_default table using the word_monitor_plugin key. All that is
    // implementation details however, all we care about it that caching for
    // our plugin definition is taken care of by this call.
    $this->setCacheBackend($cache_backend, 'word_monitor_plugin');
  }

}
