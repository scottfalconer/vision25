<?php

declare(strict_types=1);

namespace Drupal\vision25_site\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;

#[Block(
  id: 'vision25_media_interlude',
  admin_label: new \Drupal\Core\StringTranslation\TranslatableMarkup('Vision 25 Media Interlude')
)]
/**
 * Provides the media interlude block.
 */
final class Vision25MediaInterludeBlock extends AbstractVision25Block {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $filename = (string) ($this->configuration['image_filename'] ?? '');
    return $this->buildSdcComponent(
      'vision25:vision25-media-interlude',
      [
        'image_url' => $this->repository->themeAssetUrl($filename),
        'image_alt' => (string) ($this->configuration['image_alt'] ?? ''),
      ],
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableSettings(): array {
    return [
      'image_filename' => [
        'type' => 'textfield',
        'title' => 'Theme image filename',
        'description' => 'Image filename from the custom theme images directory.',
        'default' => 'bluehour-beams-011.png',
      ],
      'image_alt' => [
        'type' => 'textfield',
        'title' => 'Image alt text',
        'description' => 'Alt text for the interlude image. Leave empty for decorative imagery.',
        'default' => '',
      ],
    ];
  }

}
