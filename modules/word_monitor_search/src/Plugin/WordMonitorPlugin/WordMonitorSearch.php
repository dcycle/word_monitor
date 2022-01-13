<?php

namespace Drupal\word_monitor_search\Plugin\WordMonitorPlugin;

use Drupal\Core\Entity\EntityTypeManager;
use Drupal\search\Entity\SearchPage;
use Drupal\search\SearchPageRepository;
use Drupal\word_monitor\WordMonitorPluginBase;
use Drupal\word_monitor_search\WordMonitorSearch as WordMonitorSearchSingleton;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Integrates Word Monitor with core search.
 *
 * @WordMonitorPluginAnnotation(
 *   id = "word_monitor_search",
 *   weight = 0,
 *   description = @Translation("Integrates Word Monitor with core search."),
 * )
 */
class WordMonitorSearch extends WordMonitorPluginBase {

  /**
   * The injected search.search_page_repository service.
   *
   * @var \Drupal\search\SearchPageRepository
   */
  protected $searchPageRepository;

  /**
   * The injected word_monitor_search service.
   *
   * @var \Drupal\word_monitor_search\WordMonitorSearch
   */
  protected $wordMonitorSearchSingleton;

  /**
   * Constructs a new WordMonitorSearch object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManager $entity_type_manager
   *   An injected entity_type.manager service.
   * @param \Drupal\search\SearchPageRepository $search_page_repository
   *   An injected entity_type.manager service.
   * @param \Drupal\word_monitor_search\WordMonitorSearch $word_monitor_search_singleton
   *   An injected word_monitor_search service.
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(EntityTypeManager $entity_type_manager, SearchPageRepository $search_page_repository, WordMonitorSearchSingleton $word_monitor_search_singleton, array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($entity_type_manager, $configuration, $plugin_id, $plugin_definition);
    $this->searchPageRepository = $search_page_repository;
    $this->wordMonitorSearchSingleton = $word_monitor_search_singleton;
  }

  /**
   * Normalize a result from a search page and add it to our results array.
   *
   * @param array $results
   *   Existing results, in a format where each element contains:
   *   * id: the entity id.
   *   * entity: the entity.
   *   * entity_type: the entity type.
   *   * title: the entity's title.
   *   * path: path to the entity.
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
    $this->wordMonitorSearchSingleton->plugins()->addResult($results, $search_page_id, $result);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $entity_type_manager = $container->get('entity_type.manager');
    $search_page_repository = $container->get('search.search_page_repository');
    $word_monitor_search = $container->get('word_monitor_search');

    // PHPStan complains that using this should make the class final, but
    // this is widely used in Drupal, for example in
    // ./core/lib/Drupal/Core/Entity/Controller/EntityController.php.
    // Declaring the class final would make it unmockable.
    // @phpstan-ignore-next-line
    return new static($entity_type_manager, $search_page_repository, $word_monitor_search, $configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public function find(string $term) : array {
    $return = [];
    foreach (SearchPage::loadMultiple() as $search_page_id => $search_page) {
      $plugin = $search_page->getPlugin();
      $plugin->setSearch($term, [], []);
      foreach ($plugin->buildResults() as $result) {
        $this->addResult($return, $search_page_id, $result);
      }
    }
    return $return;
  }

  /**
   * {@inheritdoc}
   */
  protected function index() {
    foreach ($this->searchPageRepository->getIndexableSearchPages() as $entity) {
      // See https://www.drupal.org/project/drupal/issues/3111190.
      // @phpstan-ignore-next-line
      $entity->getPlugin()->updateIndex();
    }
  }

}
