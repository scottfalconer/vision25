<?php

declare(strict_types=1);

use Drupal\canvas\Entity\Component;
use Drupal\canvas\Entity\Page;
use Drupal\file\Entity\File;
use Drupal\media\Entity\Media;
use Drupal\menu_link_content\Entity\MenuLinkContent;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;

$data = require __DIR__ . '/data.php';
$module_handler = \Drupal::moduleHandler();

if (!$module_handler->moduleExists('vision25_site')) {
  throw new RuntimeException('Enable the vision25_site module before running this script.');
}

assertSeedPrerequisites();
ensureCanvasComponents(extractCanvasComponentIds($data['pages']));

$track_terms = [];
foreach ($data['tracks'] as $track) {
  $track_terms[$track['id']] = ensureTerm('track', $track['id'], $track['name'], [
    'weight' => $track['weight'],
    'field_summary' => $track['summary'],
    'field_accent_hint' => $track['accent_hint'],
    'field_image' => ensureMediaImage(mediaFilename($track['shot_code']), $track['name']),
  ]);
}

$rooms = [];
foreach ($data['rooms'] as $room) {
  $rooms[$room['id']] = ensureTerm('room', $room['id'], $room['name']);
}

$labs = [];
foreach ($data['labs'] as $lab) {
  $labs[$lab['id']] = ensureNode('lab', $lab['id'], $lab['title'], [
    'field_track' => targetId($track_terms[$lab['track_id']]),
    'field_sort_order' => valueField((string) $lab['sort_order']),
    'field_tagline' => valueField($lab['tagline']),
    'field_description' => valueField($lab['description']),
    'field_signals' => array_map(static fn(string $value): array => ['value' => $value], $lab['signals']),
    'field_image' => targetId(ensureMediaImage(mediaFilename($lab['shot_code']), $lab['title'])),
    'path' => ['pathauto' => 1],
  ]);
}

foreach ($data['sessions'] as $session) {
  ensureNode('session', $session['id'], $session['title'], [
    'field_session_type' => valueField($session['type']),
    'field_track' => targetId($track_terms[$session['track_id']]),
    'field_lab' => targetId($labs[$session['lab_id']]),
    'field_room' => targetId($rooms[$session['room_id']]),
    'field_start' => valueField(toDateTime($session['start'])),
    'field_end' => valueField(toDateTime($session['end'])),
    'field_summary' => valueField($session['summary']),
    'field_image' => targetId(ensureMediaImage(mediaFilename($session['shot_code']), $session['title'])),
    'path' => ['pathauto' => 1],
  ]);
}

foreach ($data['milestones'] as $milestone) {
  ensureNode('milestone', $milestone['id'], $milestone['title'], [
    'field_era' => valueField($milestone['era']),
    'field_year' => valueField($milestone['year']),
    'field_image' => targetId(ensureMediaImage(mediaFilename($milestone['shot_code']), $milestone['title'])),
    'path' => ['pathauto' => 1],
  ]);
}

foreach ($data['pages'] as $page_definition) {
  $page = ensureCanvasPage(
    $page_definition['title'],
    $page_definition['description'],
    $page_definition['components'] ?? [['component_id' => $page_definition['component_id']]],
    $page_definition['alias'],
  );

  if ($page_definition['is_front']) {
    \Drupal::configFactory()->getEditable('system.site')
      ->set('page.front', $page_definition['alias'] ?: '/page/' . $page->id())
      ->save();
  }

  if ($page_definition['menu_title'] && $page_definition['menu_path']) {
    ensureMenuLink($page_definition['menu_title'], $page_definition['menu_path'], $page_definition['menu_weight'] ?? 0);
  }
}

\Drupal::service('cache_tags.invalidator')->invalidateTags([
  'rendered',
  'menu_link_content_list',
  'config:system.site',
]);

print "Vision 25 provisioning complete.\n";

function assertSeedPrerequisites(): void {
  assertConfigEntityExists('taxonomy_vocabulary', 'track', 'Tracks vocabulary');
  assertConfigEntityExists('taxonomy_vocabulary', 'room', 'Rooms vocabulary');
  assertConfigEntityExists('node_type', 'lab', 'Lab content type');
  assertConfigEntityExists('node_type', 'session', 'Session content type');
  assertConfigEntityExists('node_type', 'milestone', 'Milestone content type');
  assertConfigEntityExists('webform', 'vision25_register', 'Vision 25 register webform');
  assertConfigEntityExists('pathauto_pattern', 'vision25_lab', 'Vision 25 Labs pathauto pattern');
  assertConfigEntityExists('pathauto_pattern', 'vision25_session', 'Vision 25 Sessions pathauto pattern');
  assertConfigEntityExists('pathauto_pattern', 'vision25_milestone', 'Vision 25 Milestones pathauto pattern');

  assertBundleFields('taxonomy_term', 'track', ['field_source_id', 'field_summary', 'field_accent_hint', 'field_image']);
  assertBundleFields('taxonomy_term', 'room', ['field_source_id']);
  assertBundleFields('node', 'lab', ['field_source_id', 'field_track', 'field_sort_order', 'field_tagline', 'field_description', 'field_signals', 'field_image']);
  assertBundleFields('node', 'session', ['field_source_id', 'field_track', 'field_lab', 'field_room', 'field_session_type', 'field_start', 'field_end', 'field_summary', 'field_image']);
  assertBundleFields('node', 'milestone', ['field_source_id', 'field_era', 'field_year', 'field_image']);
}

function assertConfigEntityExists(string $entity_type_id, string $id, string $label): void {
  $entity = \Drupal::entityTypeManager()->getStorage($entity_type_id)->load($id);
  if (!$entity) {
    throw new RuntimeException("Missing required config entity: $label ($entity_type_id:$id). Run drush cim before provisioning.");
  }
}

function assertBundleFields(string $entity_type_id, string $bundle, array $field_names): void {
  $definitions = \Drupal::service('entity_field.manager')->getFieldDefinitions($entity_type_id, $bundle);
  foreach ($field_names as $field_name) {
    if (!isset($definitions[$field_name])) {
      throw new RuntimeException("Missing required field $entity_type_id.$bundle.$field_name. Run drush cim before provisioning.");
    }
  }
}

function ensureTerm(string $vid, string $source_id, string $name, array $fields = []): Term {
  $storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
  $ids = $storage->getQuery()
    ->accessCheck(FALSE)
    ->condition('vid', $vid)
    ->condition('field_source_id', $source_id)
    ->range(0, 1)
    ->execute();

  $term = $ids ? $storage->load(reset($ids)) : Term::create(['vid' => $vid]);
  \assert($term instanceof Term);
  $term->setName($name);
  $term->set('field_source_id', $source_id);
  if (isset($fields['weight'])) {
    $term->set('weight', $fields['weight']);
    unset($fields['weight']);
  }
  foreach ($fields as $field_name => $value) {
    $term->set($field_name, $value);
  }
  $term->save();
  return $term;
}

function ensureNode(string $bundle, string $source_id, string $title, array $fields = []): Node {
  $storage = \Drupal::entityTypeManager()->getStorage('node');
  $ids = $storage->getQuery()
    ->accessCheck(FALSE)
    ->condition('type', $bundle)
    ->condition('field_source_id', $source_id)
    ->range(0, 1)
    ->execute();

  $is_existing = (bool) $ids;
  $node = $ids ? $storage->load(reset($ids)) : Node::create([
    'type' => $bundle,
    'uid' => 1,
  ]);
  \assert($node instanceof Node);
  $node->setTitle($title);
  $node->set('field_source_id', $source_id);
  foreach ($fields as $field_name => $value) {
    $node->set($field_name, $value);
  }
  applySeedPublicationState($node, $is_existing);
  $node->setNewRevision(TRUE);
  $node->setRevisionLogMessage('Vision 25 seed update');
  $node->save();
  return $node;
}

function applySeedPublicationState(Node $node, bool $is_existing): void {
  $override = getenv('VISION25_SEED_MODERATION_STATE');
  $state = NULL;

  if ($override !== FALSE && trim($override) !== '') {
    $state = trim($override);
  }
  elseif ($node->hasField('moderation_state')) {
    if ($is_existing && !$node->get('moderation_state')->isEmpty()) {
      $state = (string) $node->get('moderation_state')->value;
    }
    else {
      $default_value = $node->getFieldDefinition('moderation_state')->getDefaultValue($node);
      $state = (string) ($default_value[0]['value'] ?? '');
    }
  }

  if ($state !== NULL && $state !== '') {
    $node->set('moderation_state', $state);
    $node->setPublished($state === 'published');
    return;
  }

  if (!$is_existing) {
    $node->setPublished(TRUE);
  }
}

function ensureMediaImage(string $filename, string $alt): Media {
  $storage = \Drupal::entityTypeManager()->getStorage('media');
  $ids = $storage->getQuery()
    ->accessCheck(FALSE)
    ->condition('bundle', 'image')
    ->condition('name', $filename)
    ->range(0, 1)
    ->execute();

  if ($ids) {
    $media = $storage->load(reset($ids));
    \assert($media instanceof Media);
    return $media;
  }

  $source_path = DRUPAL_ROOT . '/themes/custom/vision25/images/' . $filename;
  if (!is_file($source_path)) {
    throw new RuntimeException("Missing theme image asset: $source_path");
  }

  $directory = 'public://vision25';
  \Drupal::service('file_system')->prepareDirectory($directory, \Drupal\Core\File\FileSystemInterface::CREATE_DIRECTORY | \Drupal\Core\File\FileSystemInterface::MODIFY_PERMISSIONS);
  $destination = "$directory/$filename";
  $copied_uri = \Drupal::service('file_system')->copy($source_path, $destination, \Drupal\Core\File\FileExists::Replace);

  $files = \Drupal::entityTypeManager()->getStorage('file')->loadByProperties(['uri' => $copied_uri]);
  $file = $files ? reset($files) : File::create(['uri' => $copied_uri]);
  \assert($file instanceof File);
  $file->setPermanent();
  $file->save();

  $media = Media::create([
    'bundle' => 'image',
    'name' => $filename,
    'status' => 1,
    'field_media_image' => [
      'target_id' => $file->id(),
      'alt' => $alt,
    ],
  ]);
  $media->save();
  return $media;
}

function mediaFilename(string $shot_code): string {
  static $image_map;
  $image_map ??= require __DIR__ . '/data.php';
  $filename = $image_map['image_map'][$shot_code] ?? NULL;
  if (!$filename) {
    throw new RuntimeException("Unknown shot code: $shot_code");
  }
  return $filename;
}

function toDateTime(string $time): string {
  return '2025-10-25T' . $time . ':00';
}

function targetId(object $entity): array {
  return ['target_id' => $entity->id()];
}

function valueField(string $value): array {
  return ['value' => $value];
}

function ensureCanvasPage(string $title, string $description, array $component_definitions, ?string $alias): Page {
  $storage = \Drupal::entityTypeManager()->getStorage('canvas_page');
  $ids = $storage->getQuery()
    ->accessCheck(FALSE)
    ->condition('title', $title)
    ->range(0, 1)
    ->execute();

  $page = $ids ? $storage->load(reset($ids)) : Page::create([
    'title' => $title,
    'owner' => 1,
  ]);
  \assert($page instanceof Page);

  $page->set('title', $title);
  $page->set('description', $description);
  $page->set('status', TRUE);
  $page->setNewRevision(TRUE);
  $page->setRevisionLogMessage('Vision 25 seed update');
  $page->setComponentTree(buildCanvasPageComponents($component_definitions));
  if ($alias) {
    $page->set('path', ['alias' => $alias, 'pathauto' => 0]);
  }
  else {
    $page->set('path', ['alias' => '', 'pathauto' => 0]);
  }
  $page->save();
  return $page;
}

function buildCanvasPageComponents(array $component_definitions): array {
  $tree = [];
  foreach ($component_definitions as $definition) {
    $component_id = (string) ($definition['component_id'] ?? '');
    $component = Component::load($component_id);
    if (!$component) {
      throw new RuntimeException("Missing Canvas component: $component_id. Run drush cr after enabling vision25_site.");
    }
    $default_inputs = [];
    if ($component->get('source') === 'block') {
      $default_inputs = $component->get('settings')['default_settings'] ?? [];
    }
    $tree[] = [
      'uuid' => \Drupal::service('uuid')->generate(),
      'component_id' => $component_id,
      'component_version' => $component->getActiveVersion(),
      'inputs' => array_replace($default_inputs, $definition['inputs'] ?? []),
    ];
  }
  return $tree;
}

/**
 * Ensures the Canvas component config entities exist for the requested IDs.
 *
 * @param array<int, string> $component_ids
 *   Canvas component config entity IDs referenced by provisioned pages.
 */
function ensureCanvasComponents(array $component_ids): void {
  $source_specific_ids = [];
  foreach (array_values(array_unique($component_ids)) as $component_id) {
    if (str_starts_with($component_id, 'sdc.')) {
      $source_specific_ids['sdc'][] = \Drupal\canvas\Plugin\Canvas\ComponentSource\SingleDirectoryComponentDiscovery::getSourceSpecificComponentId($component_id);
      continue;
    }

    if (str_starts_with($component_id, 'block.')) {
      $source_specific_ids['block'][] = \Drupal\canvas\Plugin\Canvas\ComponentSource\BlockComponentDiscovery::getSourceSpecificComponentId($component_id);
    }
  }

  if ($source_specific_ids === []) {
    return;
  }

  $manager = \Drupal::service(\Drupal\canvas\ComponentSource\ComponentSourceManager::class);
  \assert($manager instanceof \Drupal\canvas\ComponentSource\ComponentSourceManager);
  foreach ($source_specific_ids as $source_id => $ids) {
    $manager->generateComponents($source_id, array_values(array_unique($ids)));
  }
}

/**
 * Extracts the Canvas component IDs referenced by the configured pages.
 *
 * @param array<int, array<string, mixed>> $pages
 *   The page definitions.
 *
 * @return array<int, string>
 *   The referenced Canvas component IDs.
 */
function extractCanvasComponentIds(array $pages): array {
  $component_ids = [];
  foreach ($pages as $page_definition) {
    foreach ($page_definition['components'] ?? [] as $component_definition) {
      $component_id = (string) ($component_definition['component_id'] ?? '');
      if ($component_id !== '') {
        $component_ids[] = $component_id;
      }
    }
  }
  return $component_ids;
}

function ensureMenuLink(string $title, string $path, int $weight): void {
  $storage = \Drupal::entityTypeManager()->getStorage('menu_link_content');
  $ids = $storage->getQuery()
    ->accessCheck(FALSE)
    ->condition('menu_name', 'main')
    ->condition('title', $title)
    ->range(0, 1)
    ->execute();

  $link = $ids ? $storage->load(reset($ids)) : MenuLinkContent::create([
    'menu_name' => 'main',
  ]);
  \assert($link instanceof MenuLinkContent);
  $link->set('title', $title);
  $link->set('link', ['uri' => 'internal:' . $path]);
  $link->set('weight', $weight);
  $link->set('enabled', TRUE);
  $link->save();
}
