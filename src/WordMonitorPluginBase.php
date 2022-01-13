<?php

namespace Drupal\word_monitor;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * A base class to help developers implement WordMonitorPlugin objects.
 *
 * @see \Drupal\word_monitor\Annotation\WordMonitorPluginAnnotation
 * @see \Drupal\word_monitor\WordMonitorPluginInterface
 */
abstract class WordMonitorPluginBase extends PluginBase implements WordMonitorPluginInterface, ContainerFactoryPluginInterface {

  /**
   * The injected entity_type.manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * Array of entities for the self-test.
   *
   * @var array
   */
  protected $selfTestEntities;

  /**
   * Constructs a new WordMonitorPluginBase object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManager $entity_type_manager
   *   An injected entity_type.manager service.
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(EntityTypeManager $entity_type_manager, array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->selfTestEntities = [
      'wordone' => [
        'find' => [
          'wordone' => TRUE,
          'wordo' => FALSE,
          'ordone' => FALSE,
        ],
      ],
      'wordtwo' => [
        'find' => [
          'wordone' => FALSE,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $entity_type_manager = $container->get('entity_type.manager');
    // PHPStan complains that using this should make the class final, but
    // this is widely used in Drupal, for example in
    // ./core/lib/Drupal/Core/Entity/Controller/EntityController.php.
    // Declaring the class final would make it unmockable.
    // @phpstan-ignore-next-line
    return new static($entity_type_manager, $configuration, $plugin_id, $plugin_definition);
  }

  /**
   * Find a search term.
   *
   * @return array
   *   An array, where each item will contain the following keys:
   *   * id: the ID of the entity.
   *   * entity: the entity object.
   *   * entity_type: the entity type such as node or user.
   *   * title: the entity's title.
   *   * path: the entity's path.
   *
   * @throws \Exception
   */
  abstract protected function find(string $term) : array;

  /**
   * Helper function to return some random gibberish.
   *
   * Used during self-tests.
   *
   * @return string
   *   A string of gibberish.
   */
  public function gibberish() : string {
    $gibberish_strings = [
      'Lorem ipsum dolor sit amet',
      'consectetur adipiscing elit',
      'sed do eiusmod tempor incididunt',
    ];
    return $gibberish_strings[rand(0, count($gibberish_strings) - 1)];
  }

  /**
   * {@inheritdoc}
   */
  public function selfTest() {
    print_r('Self-test setup on ' . get_class($this) . PHP_EOL);
    $this->selfTestSetup();
    print_r('Self-test run on ' . get_class($this) . PHP_EOL);
    $this->selfTestRun();
    print_r('Self-test teardown on ' . get_class($this) . PHP_EOL);
    $this->selfTestTeardown();
  }

  /**
   * Helper function to create an entity with a given word.
   *
   * @param string $word
   *   A word to be included in the entity's field(s).
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *   A newly-created entity.
   */
  protected function selfTestEntityCreate(string $word) : EntityInterface {
    $node = $this->entityTypeManager->getStorage('node')->create([
      'type' => 'article',
      'title' => $this->gibberish(),
      'body' => $this->gibberish() . ' ' . $word . ' ' . $this->gibberish(),
    ]);
    $node->save();
    return $node;
  }

  /**
   * Perform all indexing on for this search mechanism.
   *
   * This is meant to be performed during self-tests on a CI environment.
   * On real-word environments, indexing will happen during cron.
   */
  abstract protected function index();

  /**
   * Set-up self-tests.
   */
  protected function selfTestSetup() {
    print_r('Creating dummy entities' . PHP_EOL);
    foreach (array_keys($this->selfTestEntities) as $word) {
      $this->selfTestEntities[$word]['entity'] = $this->selfTestEntityCreate($word);
    }
    print_r('Indexing dummy entities' . PHP_EOL);
    $this->index();
  }

  /**
   * Run self-tests on a plugin after setup and before teardown.
   */
  protected function selfTestRun() {
    foreach ($this->selfTestEntities as $word => $info) {
      foreach ($info['find'] as $search_term => $expected) {
        if (array_key_exists('node:' . $info['entity']->id(), $this->find($search_term)) != $expected) {
          print_r('During self-test, we were expecting ' . ($expected ? '' : 'not ') . 'to get node:' . $info['entity']->id() . ' (which contains the word "' . $word . '") as a result for the search term "' . $search_term . '", but we did' . ($expected ? ' not' : '') . '; actual results were ' . implode(array_keys($this->find($search_term))) . '.' . PHP_EOL);
          exit(1);
        }
        else {
          print_r('Searching for "' . $search_term . '" ' . ($expected ? 'yields' : 'does not yield') . ' node ' . $info['entity']->id() . ', as expected.' . PHP_EOL);
        }
      }
    }
  }

  /**
   * Tear down all data associated with self-tests.
   */
  protected function selfTestTeardown() {
    print_r('Deleting dummy entities' . PHP_EOL);
    foreach ($this->selfTestEntities as $info) {
      $info['entity']->delete();
    }
  }

}
