<?php

declare(strict_types=1);

namespace Drupal\vision25_site\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;

#[Block(
  id: 'vision25_register_form',
  admin_label: new \Drupal\Core\StringTranslation\TranslatableMarkup('Vision 25 Register Form')
)]
/**
 * Provides the embedded register form block.
 */
final class Vision25RegisterFormBlock extends AbstractVision25Block {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $webform = $this->loadWebform('vision25_register');
    $props = $this->getConfiguredSettings() + [
      'form' => $webform ? $webform->getSubmissionForm() : ['#markup' => $this->t('Registration form unavailable.')],
    ];
    return $this->buildSdcComponent(
      'vision25:vision25-register-form',
      $props,
      $webform ? $webform->getCacheTags() : [],
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableSettings(): array {
    return [
      'note' => [
        'type' => 'textarea',
        'title' => 'Register note',
        'description' => 'Privacy or footnote copy beneath the form.',
        'default' => 'Your information is handled with care. This is a concept demo — no data is stored or transmitted.',
      ],
    ];
  }

}
