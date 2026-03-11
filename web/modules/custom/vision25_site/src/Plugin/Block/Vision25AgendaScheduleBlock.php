<?php

declare(strict_types=1);

namespace Drupal\vision25_site\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;

#[Block(
  id: 'vision25_agenda_schedule',
  admin_label: new \Drupal\Core\StringTranslation\TranslatableMarkup('Vision 25 Agenda Schedule')
)]
/**
 * Provides the agenda schedule block.
 */
final class Vision25AgendaScheduleBlock extends AbstractVision25Block {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $tracks = $this->repository->getTracks();
    $sessions = $this->repository->getSessions();
    return $this->buildSdcComponent(
      'vision25:vision25-agenda-schedule',
      [
        'tracks' => $tracks['items'],
        'slots' => $this->repository->getGroupedSessions(),
      ],
      array_merge($tracks['cache_tags'], $sessions['cache_tags']),
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableSettings(): array {
    return [];
  }

}
