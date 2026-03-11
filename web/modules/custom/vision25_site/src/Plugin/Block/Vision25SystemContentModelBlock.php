<?php

declare(strict_types=1);

namespace Drupal\vision25_site\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;

#[Block(
  id: 'vision25_system_content_model',
  admin_label: new \Drupal\Core\StringTranslation\TranslatableMarkup('Vision 25 System Content Model')
)]
/**
 * Provides the system content model block.
 */
final class Vision25SystemContentModelBlock extends AbstractVision25Block {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    return $this->buildSdcComponent(
      'vision25:vision25-content-model',
      $this->getConfiguredSettings(),
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableSettings(): array {
    return [
      'content_model_title' => [
        'type' => 'textfield',
        'title' => 'Content model heading',
        'description' => 'Heading above the content model relationships.',
        'default' => 'Content Model',
      ],
      'quote' => [
        'type' => 'textarea',
        'title' => 'System quote',
        'description' => 'Closing quote below the content model.',
        'default' => 'A beautiful flat site is a dead end. A structured system scales for teams.',
      ],
      'relationship_1_from' => [
        'type' => 'textfield',
        'title' => 'Relationship one from',
        'description' => 'Source entity label for the first content relationship.',
        'default' => 'Labs',
      ],
      'relationship_1_label' => [
        'type' => 'textfield',
        'title' => 'Relationship one label',
        'description' => 'Connector label for the first content relationship.',
        'default' => 'referenced by',
      ],
      'relationship_1_to' => [
        'type' => 'textfield',
        'title' => 'Relationship one to',
        'description' => 'Target entity label for the first content relationship.',
        'default' => 'Sessions',
      ],
      'relationship_2_from' => [
        'type' => 'textfield',
        'title' => 'Relationship two from',
        'description' => 'Source entity label for the second content relationship.',
        'default' => 'Sessions',
      ],
      'relationship_2_label' => [
        'type' => 'textfield',
        'title' => 'Relationship two label',
        'description' => 'Connector label for the second content relationship.',
        'default' => 'categorized by',
      ],
      'relationship_2_to' => [
        'type' => 'textfield',
        'title' => 'Relationship two to',
        'description' => 'Target entity label for the second content relationship.',
        'default' => 'Tracks',
      ],
      'relationship_3_from' => [
        'type' => 'textfield',
        'title' => 'Relationship three from',
        'description' => 'Source entity label for the third content relationship.',
        'default' => 'Milestones',
      ],
      'relationship_3_label' => [
        'type' => 'textfield',
        'title' => 'Relationship three label',
        'description' => 'Connector label for the third content relationship.',
        'default' => 'tagged Past / Future',
      ],
      'relationship_3_to' => [
        'type' => 'textfield',
        'title' => 'Relationship three to',
        'description' => 'Target entity label for the third content relationship.',
        'default' => 'Era',
      ],
    ];
  }

}
