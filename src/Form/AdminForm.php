<?php

namespace Drupal\word_monitor\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\word_monitor\WordMonitor;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The Word Monitor admin form.
 */
class AdminForm extends FormBase {

  /**
   * The injected word_monitor service.
   *
   * @var \Drupal\word_monitor\WordMonitor
   */
  protected $wordMonitor;

  /**
   * Class constructor.
   *
   * @param \Drupal\word_monitor\WordMonitor $word_monitor
   *   The injected word_monitor service.
   */
  public function __construct(WordMonitor $word_monitor) {
    $this->wordMonitor = $word_monitor;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // PHPStan complains that using this should make the class final, but
    // this is widely used in Drupal, for example in
    // ./core/lib/Drupal/Core/Entity/Controller/EntityController.php.
    // Declaring the class final would make it unmockable.
    // @phpstan:ignoreError
    return new static(
      $container->get('word_monitor')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'word_monitor_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $all = $this->wordMonitor->findAll();
    $formatted_for_table = [];

    foreach ($all as $row) {
      $formatted_row = [];
      $formatted_row[] = $row['word'];
      $formatted_row[] = $row['link'];
      $formatted_for_table[] = $formatted_row;
    }

    $form['#cache']['max-age'] = 0;
    $form['word_monitor_reports'] = [
      '#type' => 'table',
      '#empty' => $this->t('No items to report!'),
      '#header' => [
        'Word',
        'Link',
      ],
      '#rows' => $formatted_for_table,
    ];
    $form['word_monitor_words'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Words to monitor (one per line)'),
      '#description' => $this->t('Enter one word per line; the system will continually search your site for these.'),
      '#default_value' => $this->wordsToMonitor(),
    ];
    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $input = $form_state->getUserInput();
    $this->wordMonitor->setWords(explode(PHP_EOL, $input['word_monitor_words']));
  }

  /**
   * Fetch words to monitor separated by a newline.
   *
   * @return string
   *   Words to monitor separated by a newline.
   */
  public function wordsToMonitor() : string {
    return implode(PHP_EOL, $this->wordMonitor->getWords());
  }

}
