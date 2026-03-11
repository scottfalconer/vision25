<?php

declare(strict_types=1);

use Drupal\media\MediaInterface;
use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;

$author = ensureDemoEditor();
$result = upsertDemoMilestone((int) $author->id());
$node = $result['node'];
\assert($node instanceof Node);
$alias = \Drupal::service('path_alias.manager')->getAliasByPath('/node/' . $node->id());

print json_encode([
  'operation' => $result['operation'],
  'author' => $author->getAccountName(),
  'author_uid' => (int) $author->id(),
  'nid' => (int) $node->id(),
  'title' => $node->label(),
  'moderation_state' => (string) ($node->get('moderation_state')->value ?? ''),
  'published' => (bool) $node->isPublished(),
  'alias' => $alias,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";

function ensureDemoEditor(): User {
  $storage = \Drupal::entityTypeManager()->getStorage('user');
  $ids = $storage->getQuery()
    ->accessCheck(FALSE)
    ->condition('name', 'vision25_demo_editor')
    ->range(0, 1)
    ->execute();

  $user = $ids ? $storage->load(reset($ids)) : User::create([
    'name' => 'vision25_demo_editor',
    'mail' => 'vision25-demo-editor@example.test',
    'status' => 1,
    'pass' => bin2hex(random_bytes(16)),
  ]);
  \assert($user instanceof User);

  if (!$user->hasRole('content_editor')) {
    $user->addRole('content_editor');
  }

  $user->activate();
  $user->save();

  return $user;
}

/**
 * @return array{node: \Drupal\node\Entity\Node, operation: string}
 */
function upsertDemoMilestone(int $owner_id): array {
  $storage = \Drupal::entityTypeManager()->getStorage('node');
  $source_id = 'demo_drupalcon_2026_chicago';
  $ids = $storage->getQuery()
    ->accessCheck(FALSE)
    ->condition('type', 'milestone')
    ->condition('field_source_id', $source_id)
    ->range(0, 1)
    ->execute();

  $node = $ids ? $storage->load(reset($ids)) : Node::create(['type' => 'milestone']);
  \assert($node instanceof Node);

  $node->setOwnerId($owner_id);
  $node->setTitle('DrupalCon 2026 Chicago');
  $node->set('field_source_id', $source_id);
  $node->set('field_era', 'future');
  $node->set('field_year', '2026');
  $node->set('path', ['pathauto' => 1]);

  $image_media_id = findDemoImageMediaId();
  if ($image_media_id !== NULL) {
    $node->set('field_image', ['target_id' => $image_media_id]);
  }

  $node->setNewRevision(TRUE);
  $node->setRevisionUserId($owner_id);
  $node->setRevisionLogMessage('Demo recording: add and publish DrupalCon 2026 Chicago timeline milestone.');
  $node->set('moderation_state', 'published');
  $node->setPublished(TRUE);
  $node->save();

  return [
    'node' => $node,
    'operation' => $ids ? 'updated' : 'created',
  ];
}

function findDemoImageMediaId(): ?int {
  $storage = \Drupal::entityTypeManager()->getStorage('media');
  $ids = $storage->getQuery()
    ->accessCheck(FALSE)
    ->condition('bundle', 'image')
    ->condition('name', 'bluehour-beams-040.png')
    ->range(0, 1)
    ->execute();

  if (!$ids) {
    return NULL;
  }

  $media = $storage->load(reset($ids));
  \assert($media instanceof MediaInterface);
  return (int) $media->id();
}
