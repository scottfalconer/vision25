<?php

declare(strict_types=1);

namespace Drupal\vision25_site\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;

#[Block(
  id: 'vision25_section_intro',
  admin_label: new \Drupal\Core\StringTranslation\TranslatableMarkup('Vision 25 Section Intro')
)]
/**
 * Provides the section intro block.
 */
final class Vision25SectionIntroBlock extends AbstractVision25Block {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    return $this->buildSdcComponent(
      'vision25:vision25-section-intro',
      $this->getConfiguredSettings(),
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableSettings(): array {
    return [
      'page_label' => [
        'type' => 'textfield',
        'title' => 'Section label',
        'description' => 'Small label above the page heading.',
        'default' => 'Section',
      ],
      'title' => [
        'type' => 'textfield',
        'title' => 'Section title',
        'description' => 'Main heading for the section intro.',
        'default' => 'Title',
      ],
      'subtitle' => [
        'type' => 'textarea',
        'title' => 'Section subtitle',
        'description' => 'Supporting copy for the section intro.',
        'default' => '',
      ],
      'layout' => [
        'type' => 'select',
        'title' => 'Layout',
        'description' => 'Controls alignment and container width for the intro block.',
        'default' => 'stack',
        'options' => [
          'stack' => 'Wide / left aligned',
          'stack_center' => 'Wide / centered',
          'register' => 'Register / centered',
        ],
      ],
    ];
  }

}
