<?php

declare(strict_types=1);

namespace Drupal\vision25_site\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;

#[Block(
  id: 'vision25_home_hero',
  admin_label: new \Drupal\Core\StringTranslation\TranslatableMarkup('Vision 25 Home Hero')
)]
/**
 * Provides the home hero block.
 */
final class Vision25HomeHeroBlock extends AbstractVision25Block {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $props = $this->getConfiguredSettings() + [
      'image_url' => $this->repository->themeAssetUrl('bluehour-beams-001.png'),
      'image_alt' => (string) ($this->configuration['hero_title'] ?? 'VISION 25'),
    ];
    return $this->buildSdcComponent('vision25:vision25-hero', $props);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableSettings(): array {
    return [
      'hero_eyebrow' => [
        'type' => 'textfield',
        'title' => 'Hero eyebrow',
        'description' => 'Short mono label above the hero title.',
        'default' => 'Drupal 2051 — The Enterprise Innovation Summit',
      ],
      'hero_title' => [
        'type' => 'textfield',
        'title' => 'Hero title',
        'description' => 'Primary home hero heading.',
        'default' => 'VISION 25',
      ],
      'hero_subtitle' => [
        'type' => 'textarea',
        'title' => 'Hero subtitle',
        'description' => 'Hero supporting copy.',
        'default' => 'The baseline of ambition shifted. We step it up.',
      ],
      'hero_cta_label' => [
        'type' => 'textfield',
        'title' => 'Hero CTA label',
        'description' => 'Label for the primary home CTA.',
        'default' => 'Register Now',
      ],
      'hero_cta_url' => [
        'type' => 'textfield',
        'title' => 'Hero CTA URL',
        'description' => 'Destination for the primary home CTA.',
        'default' => '/register',
      ],
    ];
  }

}
