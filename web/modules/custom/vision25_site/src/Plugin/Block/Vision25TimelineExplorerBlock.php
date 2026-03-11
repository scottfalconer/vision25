<?php

declare(strict_types=1);

namespace Drupal\vision25_site\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;

#[Block(
  id: 'vision25_timeline_explorer',
  admin_label: new \Drupal\Core\StringTranslation\TranslatableMarkup('Vision 25 Timeline Explorer')
)]
/**
 * Provides the timeline explorer block.
 */
final class Vision25TimelineExplorerBlock extends AbstractVision25Block {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $past = $this->repository->getMilestones('past');
    $future = $this->repository->getMilestones('future');
    $props = $this->getConfiguredSettings() + [
      'past_milestones' => $past['items'],
      'future_milestones' => $future['items'],
    ];
    return $this->buildSdcComponent(
      'vision25:vision25-timeline-explorer',
      $props,
      array_merge($past['cache_tags'], $future['cache_tags']),
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableSettings(): array {
    return [
      'past_label' => [
        'type' => 'textfield',
        'title' => 'Past toggle label',
        'description' => 'Label for the past timeline toggle.',
        'default' => 'Past 25',
      ],
      'future_label' => [
        'type' => 'textfield',
        'title' => 'Future toggle label',
        'description' => 'Label for the future timeline toggle.',
        'default' => 'Next 25',
      ],
    ];
  }

}
