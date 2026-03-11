<?php

declare(strict_types=1);

namespace Drupal\vision25_site\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;

#[Block(
  id: 'vision25_story_section',
  admin_label: new \Drupal\Core\StringTranslation\TranslatableMarkup('Vision 25 Story Section')
)]
/**
 * Provides the narrative story section block.
 */
final class Vision25StorySectionBlock extends AbstractVision25Block {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    return $this->buildSdcComponent(
      'vision25:vision25-narrative-section',
      $this->getConfiguredSettings(),
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableSettings(): array {
    return [
      'section_label' => [
        'type' => 'textfield',
        'title' => 'Section label',
        'description' => 'Eyebrow label for the narrative section.',
        'default' => 'Act I',
      ],
      'section_title' => [
        'type' => 'textfield',
        'title' => 'Section title',
        'description' => 'Heading for the narrative section.',
        'default' => 'The Flywheel',
      ],
      'body_primary' => [
        'type' => 'textarea',
        'title' => 'Primary paragraph',
        'description' => 'First paragraph for the narrative section.',
        'default' => '',
      ],
      'body_secondary' => [
        'type' => 'textarea',
        'title' => 'Secondary paragraph',
        'description' => 'Second paragraph for the narrative section.',
        'default' => '',
      ],
      'background_variant' => [
        'type' => 'select',
        'title' => 'Background variant',
        'description' => 'Choose the background style for the narrative section.',
        'default' => 'default',
        'options' => [
          'default' => 'Default',
          'luminous' => 'Luminous gradient',
        ],
      ],
    ];
  }

}
