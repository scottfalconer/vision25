<?php

declare(strict_types=1);

namespace Drupal\vision25_site\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;

#[Block(
  id: 'vision25_system_explorer',
  admin_label: new \Drupal\Core\StringTranslation\TranslatableMarkup('Vision 25 System Explorer')
)]
/**
 * Provides the system explorer block.
 */
final class Vision25SystemExplorerBlock extends AbstractVision25Block {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    return $this->buildSdcComponent(
      'vision25:vision25-system-explorer',
      $this->getConfiguredSettings(),
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableSettings(): array {
    return [
      'visitor_button_label' => [
        'type' => 'textfield',
        'title' => 'Visitor button label',
        'description' => 'Label for the visitor-view toggle.',
        'default' => 'Visitor View',
      ],
      'system_button_label' => [
        'type' => 'textfield',
        'title' => 'System button label',
        'description' => 'Label for the system-view toggle.',
        'default' => 'System View',
      ],
      'visitor_hero_label' => [
        'type' => 'textfield',
        'title' => 'Visitor hero placeholder',
        'description' => 'Label for the hero placeholder in the visitor view.',
        'default' => 'Hero_Cinematic',
      ],
      'visitor_header_label' => [
        'type' => 'textfield',
        'title' => 'Visitor header placeholder',
        'description' => 'Label for the header placeholder in the visitor view.',
        'default' => 'Section_Header',
      ],
      'visitor_triangle_label' => [
        'type' => 'textfield',
        'title' => 'Visitor triangle placeholder',
        'description' => 'Label for the triangle placeholder in the visitor view.',
        'default' => 'Triangle_Track_Nav',
      ],
      'visitor_bento_card_one_label' => [
        'type' => 'textfield',
        'title' => 'Visitor bento card one',
        'description' => 'Label for the first bento card placeholder.',
        'default' => 'Session_Card',
      ],
      'visitor_bento_card_two_label' => [
        'type' => 'textfield',
        'title' => 'Visitor bento card two',
        'description' => 'Label for the second bento card placeholder.',
        'default' => 'Lab_Card',
      ],
      'visitor_bento_card_three_label' => [
        'type' => 'textfield',
        'title' => 'Visitor bento card three',
        'description' => 'Label for the third bento card placeholder.',
        'default' => 'Session_Card',
      ],
      'visitor_cta_label' => [
        'type' => 'textfield',
        'title' => 'Visitor CTA placeholder',
        'description' => 'Label for the CTA band placeholder.',
        'default' => 'CTA_Band',
      ],
      'visitor_timeline_label' => [
        'type' => 'textfield',
        'title' => 'Visitor timeline placeholder',
        'description' => 'Label for the timeline placeholder.',
        'default' => 'Timeline_Rail',
      ],
      'box_hero_label' => [
        'type' => 'textfield',
        'title' => 'System hero box label',
        'description' => 'Overlay label for the hero box in system view.',
        'default' => 'Hero_Cinematic',
      ],
      'box_header_label' => [
        'type' => 'textfield',
        'title' => 'System header box label',
        'description' => 'Overlay label for the section header box in system view.',
        'default' => 'Section_Header',
      ],
      'box_triangle_label' => [
        'type' => 'textfield',
        'title' => 'System triangle box label',
        'description' => 'Overlay label for the triangle box in system view.',
        'default' => 'Triangle_Track_Nav',
      ],
      'box_bento_label' => [
        'type' => 'textfield',
        'title' => 'System bento box label',
        'description' => 'Overlay label for the bento box in system view.',
        'default' => 'Bento_Agenda_View',
      ],
      'box_cta_label' => [
        'type' => 'textfield',
        'title' => 'System CTA box label',
        'description' => 'Overlay label for the CTA box in system view.',
        'default' => 'CTA_Band',
      ],
      'box_timeline_label' => [
        'type' => 'textfield',
        'title' => 'System timeline box label',
        'description' => 'Overlay label for the timeline box in system view.',
        'default' => 'Timeline_Rail',
      ],
    ];
  }

}
