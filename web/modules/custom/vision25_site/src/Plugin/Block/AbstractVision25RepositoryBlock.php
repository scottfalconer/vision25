<?php

declare(strict_types=1);

namespace Drupal\vision25_site\Plugin\Block;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\vision25_site\Vision25Repository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a base class for Vision 25 blocks that query structured content.
 */
abstract class AbstractVision25RepositoryBlock extends AbstractVision25Block {

  /**
   * Constructs a Vision 25 repository-backed block.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    protected readonly Vision25Repository $repository,
    EntityTypeManagerInterface $entityTypeManager,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $entityTypeManager);
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

}
