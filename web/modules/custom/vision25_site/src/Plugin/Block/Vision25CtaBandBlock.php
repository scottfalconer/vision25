<?php

declare(strict_types=1);

namespace Drupal\vision25_site\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;

#[Block(
  id: 'vision25_cta_band',
  admin_label: new \Drupal\Core\StringTranslation\TranslatableMarkup('Vision 25 CTA Band')
)]
/**
 * Provides the CTA band block.
 */
final class Vision25CtaBandBlock extends AbstractVision25Block {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    return $this->buildSdcComponent(
      'vision25:vision25-cta-band',
      $this->getConfiguredSettings(),
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableSettings(): array {
    return [
      'title' => [
        'type' => 'textfield',
        'title' => 'CTA title',
        'description' => 'Main heading for the call-to-action band.',
        'default' => 'Step it up.',
      ],
      'subtitle' => [
        'type' => 'textarea',
        'title' => 'CTA subtitle',
        'description' => 'Supporting copy for the call-to-action band.',
        'default' => 'The baseline of ambition shifted. Join us.',
      ],
      'cta_label' => [
        'type' => 'textfield',
        'title' => 'CTA label',
        'description' => 'Label for the call-to-action button.',
        'default' => 'Register Now',
      ],
      'cta_url' => [
        'type' => 'textfield',
        'title' => 'CTA URL',
        'description' => 'Destination for the call-to-action button.',
        'default' => '/register',
      ],
    ];
  }

}
