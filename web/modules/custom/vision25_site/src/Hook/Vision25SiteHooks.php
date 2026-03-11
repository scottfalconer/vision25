<?php

declare(strict_types=1);

namespace Drupal\vision25_site\Hook;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\taxonomy\TermInterface;

/**
 * Hook implementations for the Vision 25 site module.
 */
final class Vision25SiteHooks {

  public function __construct(
    private readonly EntityTypeManagerInterface $entityTypeManager,
  ) {}

  /**
   * Implements hook_theme().
   */
  #[Hook('theme')]
  public function theme(): array {
    return [
      'vision25_not_found_panel' => [
        'variables' => [
          'front_page' => NULL,
        ],
        'template' => 'vision25-not-found-panel',
      ],
    ];
  }

  /**
   * Implements hook_form_alter().
   */
  #[Hook('form_alter')]
  public function formAlter(array &$form, FormStateInterface $form_state, string $form_id): void {
    if (!str_starts_with($form_id, 'webform_submission_vision25_register')) {
      return;
    }

    if (!isset($form['elements']['tracks'])) {
      return;
    }

    $storage = $this->entityTypeManager->getStorage('taxonomy_term');
    $term_ids = $storage->getQuery()
      ->accessCheck(TRUE)
      ->condition('vid', 'track')
      ->sort('weight')
      ->sort('name')
      ->execute();

    if (!$term_ids) {
      return;
    }

    $options = [];
    foreach ($storage->loadMultiple($term_ids) as $term) {
      \assert($term instanceof TermInterface);
      $options[$term->id()] = $term->label();
    }
    $form['elements']['tracks']['#options'] = $options;
  }

}
