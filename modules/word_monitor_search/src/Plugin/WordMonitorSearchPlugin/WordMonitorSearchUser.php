<?php

namespace Drupal\word_monitor_search\Plugin\WordMonitorSearchPlugin;

use Drupal\Core\Entity\EntityInterface;
use Drupal\word_monitor_search\WordMonitorSearchPluginBase;

/**
 * Converts users search results.
 *
 * @WordMonitorSearchPluginAnnotation(
 *   id = "word_monitor_search_user",
 *   weight = 0,
 *   description = @Translation("Converts users search results."),
 * )
 */
class WordMonitorSearchUser extends WordMonitorSearchPluginBase {

  /**
   * {@inheritdoc}
   */
  protected function appliesTo() : array {
    return [
      'user_search',
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function convert(array $result) : array {
    $username = $result['#result']['title'];

    $entity = $this->usernameToEntity($username);
    return [
      'id' => $entity->id(),
      'entity' => $entity,
      'entity_type' => 'user',
      'title' => $username,
      'path' => '/user/' . $entity->id(),
    ];
  }

  /**
   * Given a username, get a User object.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *   A user object.
   *
   * @throws \Exception
   */
  protected function usernameToEntity(string $username) : EntityInterface {
    $candidate = user_load_by_name($username);

    if (!$candidate) {
      throw new \Exception('No user with specified name.');
    }

    return $candidate;
  }

}
