<?php

declare(strict_types=1);

namespace Drupal\vision25_site\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Url;

#[Block(
  id: 'vision25_track_section',
  admin_label: new \Drupal\Core\StringTranslation\TranslatableMarkup('Vision 25 Track Section')
)]
/**
 * Provides the track section block.
 */
final class Vision25TrackSectionBlock extends AbstractVision25Block {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $track_id = (string) ($this->configuration['track_id'] ?? '');
    $track = NULL;
    $track_number = 1;
    foreach ($this->repository->getTracksWithContent() as $index => $candidate) {
      if (($candidate['id'] ?? '') === $track_id) {
        $track = $candidate;
        $track_number = $index + 1;
        break;
      }
    }

    $tracks = $this->repository->getTracks();
    $labs = $this->repository->getLabs();
    $sessions = $this->repository->getSessions();
    return $this->buildSdcComponent(
      'vision25:vision25-track-section',
      [
        'track' => $track,
        'track_number' => $track_number,
        'labs_url' => Url::fromUri('internal:/labs')->toString(),
      ],
      array_merge($tracks['cache_tags'], $labs['cache_tags'], $sessions['cache_tags']),
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableSettings(): array {
    return [
      'track_id' => [
        'type' => 'textfield',
        'title' => 'Track source ID',
        'description' => 'Machine-readable source identifier for the track section to render.',
        'default' => 'product',
      ],
    ];
  }

}
