<?php

declare(strict_types=1);

namespace Drupal\vision25_site\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\vision25_site\Vision25Repository;
use Drupal\webform\WebformInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides shared helpers for custom Vision 25 Canvas blocks.
 */
abstract class AbstractVision25Block extends BlockBase implements ContainerFactoryPluginInterface {
  protected const MAX_AGE = 900;

  /**
   * Constructs a Vision 25 block.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    protected readonly Vision25Repository $repository,
    protected readonly EntityTypeManagerInterface $entityTypeManager,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * Creates a block plugin instance from the service container.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): static {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('vision25_site.repository'),
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration(): array {
    $defaults = [];
    foreach ($this->getEditableSettings() as $key => $definition) {
      $defaults[$key] = $definition['default'];
    }
    return $defaults + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state): array {
    foreach ($this->getEditableSettings() as $key => $definition) {
      $form[$key] = [
        '#type' => $definition['type'],
        // phpcs:ignore Drupal.Semantics.FunctionT.NotLiteralString
        '#title' => $this->t($definition['title']),
        // phpcs:ignore Drupal.Semantics.FunctionT.NotLiteralString
        '#description' => $definition['description'] ? $this->t($definition['description']) : '',
        '#default_value' => $this->configuration[$key] ?? $definition['default'],
      ];
      if (isset($definition['options'])) {
        $form[$key]['#options'] = $definition['options'];
      }
    }
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state): void {
    foreach (array_keys($this->getEditableSettings()) as $key) {
      $this->configuration[$key] = $form_state->getValue($key);
    }
  }

  /**
   * Builds cache metadata for a block render array.
   */
  protected function buildCache(array $tags = []): array {
    return [
      'tags' => array_values(array_unique($tags)),
      'max-age' => self::MAX_AGE,
    ];
  }

  /**
   * Returns the current editable settings with defaults applied.
   *
   * @return array<string, mixed>
   *   The resolved editable settings keyed by machine name.
   */
  protected function getConfiguredSettings(): array {
    $settings = [];
    foreach ($this->getEditableSettings() as $key => $definition) {
      $settings[$key] = $this->configuration[$key] ?? $definition['default'];
    }
    return $settings;
  }

  /**
   * Builds a theme SDC render array for this block.
   *
   * @param string $component_id
   *   The SDC plugin ID, for example "vision25:vision25-hero".
   * @param array<string, mixed> $props
   *   The component props.
   * @param array<string> $cache_tags
   *   Additional cache tags.
   */
  protected function buildSdcComponent(string $component_id, array $props, array $cache_tags = []): array {
    return [
      '#type' => 'component',
      '#component' => $component_id,
      '#props' => $props,
      '#attached' => [
        'library' => [
          'core/components.' . str_replace(':', '--', $component_id),
        ],
      ],
      '#cache' => $this->buildCache($cache_tags),
    ];
  }

  /**
   * Loads a webform entity for block rendering.
   */
  protected function loadWebform(string $webform_id): ?WebformInterface {
    $webform = $this->entityTypeManager->getStorage('webform')->load($webform_id);
    \assert($webform === NULL || $webform instanceof WebformInterface);
    return $webform;
  }

  /**
   * Gets the editable settings metadata for the block form.
   *
   * @return array<string, array<string, mixed>>
   *   The editable block settings definitions keyed by machine name.
   */
  abstract protected function getEditableSettings(): array;

}
