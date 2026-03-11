<?php

declare(strict_types=1);

namespace Drupal\vision25_site\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Url;

#[Block(
  id: 'vision25_home_ecosystem',
  admin_label: new \Drupal\Core\StringTranslation\TranslatableMarkup('Vision 25 Home Ecosystem')
)]
/**
 * Provides the home ecosystem block.
 */
final class Vision25HomeEcosystemBlock extends AbstractVision25Block {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $tracks = $this->repository->getTracks();
    $props = $this->getConfiguredSettings() + [
      'tracks' => $tracks['items'],
      'tracks_url' => Url::fromUri('internal:/tracks')->toString(),
    ];
    return $this->buildSdcComponent('vision25:vision25-ecosystem', $props, $tracks['cache_tags']);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableSettings(): array {
    return [
      'ecosystem_label' => [
        'type' => 'textfield',
        'title' => 'Ecosystem label',
        'description' => 'Section label above the triangle navigation.',
        'default' => 'The Ecosystem',
      ],
      'ecosystem_title' => [
        'type' => 'textfield',
        'title' => 'Ecosystem title',
        'description' => 'Main triangle navigation heading.',
        'default' => 'Three corners. One system.',
      ],
      'ecosystem_subtitle' => [
        'type' => 'textarea',
        'title' => 'Ecosystem subtitle',
        'description' => 'Supporting copy for the triangle navigation.',
        'default' => 'Product, agencies, and community — each strengthening the others.',
      ],
    ];
  }

}
