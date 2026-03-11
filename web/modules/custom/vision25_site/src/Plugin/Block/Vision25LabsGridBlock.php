<?php

declare(strict_types=1);

namespace Drupal\vision25_site\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;

#[Block(
  id: 'vision25_labs_grid',
  admin_label: new \Drupal\Core\StringTranslation\TranslatableMarkup('Vision 25 Labs Grid')
)]
/**
 * Provides the labs grid block.
 */
final class Vision25LabsGridBlock extends AbstractVision25RepositoryBlock {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $labs = $this->repository->getLabs();
    $sessions = $this->repository->getSessions();
    $labs_with_sessions = array_map(static function (array $lab) use ($sessions): array {
      $lab['related_sessions'] = array_values(array_filter(
        $sessions['items'],
        static fn(array $session): bool => $session['lab_id'] === $lab['id']
      ));
      return $lab;
    }, $labs['items']);

    return $this->buildSdcComponent(
      'vision25:vision25-labs-grid',
      [
        'labs' => $labs_with_sessions,
      ],
      array_merge($labs['cache_tags'], $sessions['cache_tags']),
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableSettings(): array {
    return [];
  }

}
