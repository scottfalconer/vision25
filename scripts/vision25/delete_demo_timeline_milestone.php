<?php

declare(strict_types=1);

use Drupal\node\NodeInterface;

$storage = \Drupal::entityTypeManager()->getStorage('node');
$title_ids = $storage->getQuery()
  ->accessCheck(FALSE)
  ->condition('type', 'milestone')
  ->condition('title', 'DrupalCon 2026 Chicago')
  ->execute();
$source_ids = $storage->getQuery()
  ->accessCheck(FALSE)
  ->condition('type', 'milestone')
  ->condition('field_source_id', 'demo_drupalcon_2026_chicago')
  ->execute();
$ids = array_values(array_unique(array_merge(array_values($title_ids), array_values($source_ids))));

$deleted = [];
foreach ($storage->loadMultiple($ids) as $node) {
  \assert($node instanceof NodeInterface);
  $deleted[] = [
    'nid' => (int) $node->id(),
    'title' => $node->label(),
  ];
  $node->delete();
}

print json_encode([
  'deleted_count' => count($deleted),
  'deleted' => $deleted,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
