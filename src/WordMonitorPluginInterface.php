<?php

namespace Drupal\word_monitor;

/**
 * An interface for all WordMonitorPlugin type plugins.
 */
interface WordMonitorPluginInterface {

  /**
   * Run self-tests on this word monitor plugin.
   *
   * This is meant to be run from the command line like this:
   *
   * drush ev 'word_monitor()->selfTest();'
   *
   * The script will then exist with a non-zero error code in case of a
   * problem.
   */
  public function selfTest();

}
