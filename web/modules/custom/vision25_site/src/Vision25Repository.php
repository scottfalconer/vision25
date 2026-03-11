<?php

declare(strict_types=1);

namespace Drupal\vision25_site;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ExtensionPathResolver;
use Drupal\Core\File\FileUrlGeneratorInterface;
use Drupal\media\MediaInterface;
use Drupal\node\NodeInterface;
use Drupal\taxonomy\TermInterface;

final class Vision25Repository {

  public function __construct(
    private readonly EntityTypeManagerInterface $entityTypeManager,
    private readonly FileUrlGeneratorInterface $fileUrlGenerator,
    private readonly ExtensionPathResolver $extensionPathResolver,
  ) {}

  public function themeAssetUrl(string $filename): string {
    return '/' . trim($this->extensionPathResolver->getPath('theme', 'vision25'), '/') . '/images/' . ltrim($filename, '/');
  }

  public function getTracks(): array {
    $storage = $this->entityTypeManager->getStorage('taxonomy_term');
    $ids = $storage->getQuery()
      ->accessCheck(TRUE)
      ->condition('vid', 'track')
      ->sort('weight')
      ->sort('name')
      ->execute();

    $items = [];
    $cache_tags = ['taxonomy_term_list'];
    foreach ($storage->loadMultiple($ids) as $term) {
      \assert($term instanceof TermInterface);
      $items[] = [
        'id' => $this->getSourceId($term),
        'target_id' => (string) $term->id(),
        'name' => $term->label(),
        'summary' => (string) ($term->get('field_summary')->value ?? ''),
        'accent_hint' => (string) ($term->get('field_accent_hint')->value ?? ''),
        'image' => $this->extractMediaImageUrl($term, 'field_image', $this->themeAssetUrl('bluehour-beams-054.png')),
      ];
      $cache_tags = array_merge($cache_tags, $term->getCacheTags());
    }

    return [
      'items' => $items,
      'cache_tags' => array_values(array_unique($cache_tags)),
    ];
  }

  public function getLabs(): array {
    $storage = $this->entityTypeManager->getStorage('node');
    $ids = $storage->getQuery()
      ->accessCheck(TRUE)
      ->condition('type', 'lab')
      ->condition('status', 1)
      ->sort('field_sort_order')
      ->sort('title')
      ->execute();

    $tracks = $this->indexByKey($this->getTracks()['items'], 'target_id');
    $items = [];
    $cache_tags = ['node_list'];
    foreach ($storage->loadMultiple($ids) as $node) {
      \assert($node instanceof NodeInterface);
      $track = $tracks[(string) ($node->get('field_track')->target_id ?? '')] ?? NULL;
      $items[] = [
        'id' => $this->getSourceId($node),
        'target_id' => (string) $node->id(),
        'title' => $node->label(),
        'tagline' => (string) ($node->get('field_tagline')->value ?? ''),
        'description' => (string) ($node->get('field_description')->value ?? ''),
        'sort_order' => (int) ($node->get('field_sort_order')->value ?? 0),
        'track_id' => $track['id'] ?? '',
        'track_name' => $track['name'] ?? '',
        'signals' => array_values(array_filter(array_map(
          static fn(array $item): string => (string) ($item['value'] ?? ''),
          $node->get('field_signals')->getValue()
        ))),
        'image' => $this->extractMediaImageUrl($node, 'field_image', $this->themeAssetUrl('bluehour-beams-011.png')),
      ];
      $cache_tags = array_merge($cache_tags, $node->getCacheTags());
    }

    return [
      'items' => $items,
      'cache_tags' => array_values(array_unique($cache_tags)),
    ];
  }

  public function getSessions(): array {
    $storage = $this->entityTypeManager->getStorage('node');
    $ids = $storage->getQuery()
      ->accessCheck(TRUE)
      ->condition('type', 'session')
      ->condition('status', 1)
      ->sort('field_start')
      ->sort('title')
      ->execute();

    $tracks = $this->indexByKey($this->getTracks()['items'], 'target_id');
    $labs = $this->indexByKey($this->getLabs()['items'], 'target_id');
    $rooms = $this->indexByKey($this->getRooms(), 'target_id');
    $items = [];
    $cache_tags = ['node_list'];

    foreach ($storage->loadMultiple($ids) as $node) {
      \assert($node instanceof NodeInterface);
      $track = $tracks[(string) ($node->get('field_track')->target_id ?? '')] ?? NULL;
      $lab = $labs[(string) ($node->get('field_lab')->target_id ?? '')] ?? NULL;
      $room = $rooms[(string) ($node->get('field_room')->target_id ?? '')] ?? NULL;
      $start = (string) ($node->get('field_start')->value ?? '');
      $end = (string) ($node->get('field_end')->value ?? '');
      $items[] = [
        'id' => $this->getSourceId($node),
        'title' => $node->label(),
        'type' => (string) ($node->get('field_session_type')->value ?? ''),
        'summary' => (string) ($node->get('field_summary')->value ?? ''),
        'track_id' => $track['id'] ?? '',
        'track_name' => $track['name'] ?? '',
        'lab_id' => $lab['id'] ?? '',
        'lab_title' => $lab['title'] ?? '',
        'room_id' => $room['id'] ?? '',
        'room_name' => $room['name'] ?? '',
        'start' => $this->formatTime($start),
        'end' => $this->formatTime($end),
        'image' => $this->extractMediaImageUrl($node, 'field_image', $this->themeAssetUrl('bluehour-beams-018.png')),
      ];
      $cache_tags = array_merge($cache_tags, $node->getCacheTags());
    }

    return [
      'items' => $items,
      'cache_tags' => array_values(array_unique($cache_tags)),
    ];
  }

  public function getGroupedSessions(): array {
    $sessions = $this->getSessions()['items'];
    $grouped = [];
    foreach ($sessions as $session) {
      $slot = $session['start'] . '–' . $session['end'];
      $grouped[$slot][] = $session;
    }

    $items = [];
    foreach ($grouped as $slot => $group_sessions) {
      $items[] = [
        'slot' => $slot,
        'sessions' => $group_sessions,
      ];
    }

    return $items;
  }

  public function getTracksWithContent(): array {
    $tracks = $this->getTracks()['items'];
    $sessions = $this->getSessions()['items'];
    $labs = $this->getLabs()['items'];

    return array_map(static function (array $track) use ($sessions, $labs): array {
      $track['sessions'] = array_values(array_filter($sessions, static fn(array $session): bool => $session['track_id'] === $track['id']));
      $track['labs'] = array_values(array_filter($labs, static fn(array $lab): bool => $lab['track_id'] === $track['id']));
      return $track;
    }, $tracks);
  }

  public function getMilestones(string $era): array {
    $storage = $this->entityTypeManager->getStorage('node');
    $ids = $storage->getQuery()
      ->accessCheck(TRUE)
      ->condition('type', 'milestone')
      ->condition('status', 1)
      ->condition('field_era', $era)
      ->sort('field_year')
      ->execute();

    $items = [];
    $cache_tags = ['node_list'];
    foreach ($storage->loadMultiple($ids) as $node) {
      \assert($node instanceof NodeInterface);
      $items[] = [
        'id' => $this->getSourceId($node),
        'year' => (string) ($node->get('field_year')->value ?? ''),
        'title' => $node->label(),
        'image' => $this->extractMediaImageUrl($node, 'field_image', $this->themeAssetUrl('bluehour-beams-034.png')),
      ];
      $cache_tags = array_merge($cache_tags, $node->getCacheTags());
    }

    return [
      'items' => $items,
      'cache_tags' => array_values(array_unique($cache_tags)),
    ];
  }

  /**
   * @return array<int, array{id: string, target_id: string, name: string}>
   */
  private function getRooms(): array {
    $storage = $this->entityTypeManager->getStorage('taxonomy_term');
    $ids = $storage->getQuery()
      ->accessCheck(TRUE)
      ->condition('vid', 'room')
      ->sort('weight')
      ->sort('name')
      ->execute();

    $rooms = [];
    foreach ($storage->loadMultiple($ids) as $term) {
      \assert($term instanceof TermInterface);
      $rooms[] = [
        'id' => $this->getSourceId($term),
        'target_id' => (string) $term->id(),
        'name' => $term->label(),
      ];
    }

    return $rooms;
  }

  /**
   * @param array<int, array<string, mixed>> $items
   *
   * @return array<string, array<string, mixed>>
   */
  private function indexByKey(array $items, string $key): array {
    $indexed = [];
    foreach ($items as $item) {
      $indexed[(string) ($item[$key] ?? '')] = $item;
    }
    return $indexed;
  }

  private function formatTime(string $datetime): string {
    if ($datetime === '') {
      return '';
    }
    return (new \DateTimeImmutable($datetime))->format('H:i');
  }

  private function extractMediaImageUrl(EntityInterface $entity, string $field_name, string $fallback): string {
    if (!$entity->hasField($field_name) || $entity->get($field_name)->isEmpty()) {
      return $fallback;
    }

    $media = $entity->get($field_name)->entity;
    if (!$media instanceof MediaInterface) {
      return $fallback;
    }

    foreach (['field_media_image', 'field_media_svg_image'] as $source_field) {
      if ($media->hasField($source_field) && !$media->get($source_field)->isEmpty()) {
        $file = $media->get($source_field)->entity;
        if ($file) {
          return $this->fileUrlGenerator->generateString($file->getFileUri());
        }
      }
    }

    return $fallback;
  }

  private function getSourceId(EntityInterface $entity): string {
    if ($entity->hasField('field_source_id') && !$entity->get('field_source_id')->isEmpty()) {
      return (string) $entity->get('field_source_id')->value;
    }

    return (string) $entity->id();
  }
}
