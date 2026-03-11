<?php

declare(strict_types=1);

$theme_image = static fn(string $filename): string => '/themes/custom/vision25/images/' . ltrim($filename, '/');

$data = [
  'image_map' => [
    'hero' => 'bluehour-beams-001.png',
    'BLUEHOUR_BEAMS_01' => 'bluehour-beams-001.png',
    'BLUEHOUR_BEAMS_02' => 'bluehour-beams-018.png',
    'BLUEHOUR_BEAMS_03' => 'bluehour-beams-007.png',
    'BLUEHOUR_BEAMS_04' => 'bluehour-beams-040.png',
    'BLUEHOUR_BEAMS_05' => 'bluehour-beams-034.png',
    'OPTICS_REFRACTION_01' => 'bluehour-beams-054.png',
    'OPTICS_REFRACTION_02' => 'bluehour-beams-009.png',
    'OPTICS_REFRACTION_03' => 'bluehour-beams-011.png',
    'OPTICS_REFRACTION_04' => 'bluehour-beams-007.png',
    'OPTICS_REFRACTION_05' => 'bluehour-beams-034.png',
    'GLASS_PANES_01' => 'bluehour-beams-009.png',
    'GLASS_PANES_02' => 'bluehour-beams-011.png',
    'GLASS_PANES_03' => 'bluehour-beams-018.png',
    'GLASS_PANES_04' => 'bluehour-beams-054.png',
    'GLASS_PANES_05' => 'bluehour-beams-040.png',
    'GLASS_PANES_06' => 'bluehour-beams-009.png',
    'GLASS_PANES_07' => 'bluehour-beams-034.png',
    'PHYSICS_FINGERPRINTS_01' => 'bluehour-beams-007.png',
    'PHYSICS_FINGERPRINTS_02' => 'bluehour-beams-040.png',
    'PHYSICS_FINGERPRINTS_03' => 'bluehour-beams-034.png',
    'PHYSICS_FINGERPRINTS_04' => 'bluehour-beams-011.png',
    'HOOK_SET4_01' => 'bluehour-beams-054.png',
    'HOOK_SET4_03' => 'bluehour-beams-018.png',
    'HOOK_SET4_05' => 'bluehour-beams-040.png',
    'EP01_BROLL_01' => 'bluehour-beams-007.png',
    'EP01_BROLL_02' => 'bluehour-beams-009.png',
    'EP01_BROLL_03' => 'bluehour-beams-011.png',
    'EP01_BROLL_06' => 'bluehour-beams-034.png',
  ],
  'tracks' => [
    [
      'id' => 'product',
      'name' => 'Evolving the Product',
      'summary' => 'From content to context. From pages to governed systems.',
      'accent_hint' => 'OPTICS_REFRACTION',
      'shot_code' => 'OPTICS_REFRACTION_01',
      'weight' => 0,
    ],
    [
      'id' => 'agency',
      'name' => 'The Agency of the Future',
      'summary' => 'From builds to systems. From deliverables to leverage.',
      'accent_hint' => 'BLUEHOUR_BEAMS',
      'shot_code' => 'BLUEHOUR_BEAMS_01',
      'weight' => 1,
    ],
    [
      'id' => 'community',
      'name' => 'Empowering the Community',
      'summary' => 'From five great starts to a real marketplace at scale.',
      'accent_hint' => 'PHYSICS_FINGERPRINTS',
      'shot_code' => 'PHYSICS_FINGERPRINTS_01',
      'weight' => 2,
    ],
  ],
  'rooms' => [
    ['id' => 'atrium', 'name' => 'Atrium Stage'],
    ['id' => 'studio', 'name' => 'Studio Room'],
    ['id' => 'lab01', 'name' => 'Lab 01'],
    ['id' => 'lab02', 'name' => 'Lab 02'],
  ],
  'labs' => [
    [
      'id' => 'lab_context',
      'sort_order' => 0,
      'title' => 'Context Control Center',
      'tagline' => 'Context is as important as content.',
      'track_id' => 'product',
      'description' => 'A governed way to connect outside sources (knowledge bases, intranets, documentation) so teams can build AI-ready experiences with trust, traceability, and control.',
      'shot_code' => 'OPTICS_REFRACTION_03',
      'signals' => [
        'Enterprise knowledge graphs need governed ingestion pipelines',
        'AI trust requires source traceability at every layer',
        'Context-aware content outperforms keyword-driven content by 3×',
      ],
    ],
    [
      'id' => 'lab_canvas',
      'sort_order' => 1,
      'title' => 'Experience Builder Canvas',
      'tagline' => 'Beautiful layouts. Rational structure.',
      'track_id' => 'product',
      'description' => 'A composable component system that keeps ambitious visuals editable and consistent across teams without fragile one-off implementations.',
      'shot_code' => 'GLASS_PANES_02',
      'signals' => [
        'Component reuse reduces build time by 60% across teams',
        'Visual consistency drives measurable brand trust gains',
        'Composable systems outlast monolithic templates by 4× in lifespan',
      ],
    ],
    [
      'id' => 'lab_governance',
      'sort_order' => 2,
      'title' => 'Governance & Trust Layer',
      'tagline' => 'Ship fast. Stay safe.',
      'track_id' => 'product',
      'description' => 'Permissions, workflows, approvals, and auditability designed for modern teams and for AI-assisted creation so the system scales beyond a single builder.',
      'shot_code' => 'BLUEHOUR_BEAMS_01',
      'signals' => [
        'AI-generated content requires new approval workflows',
        'Audit trails are non-negotiable for regulated industries',
        'Multi-editor governance prevents 80% of content incidents',
      ],
    ],
    [
      'id' => 'lab_agency',
      'sort_order' => 3,
      'title' => 'Agency Acceleration Toolkit',
      'tagline' => 'From projects to products.',
      'track_id' => 'agency',
      'description' => 'Reusable patterns, starter kits, and operational playbooks that let agencies deliver premium experiences faster while protecting quality, accessibility, and extensibility.',
      'shot_code' => 'HOOK_SET4_05',
      'signals' => [
        'Starter kits reduce project kickoff from weeks to days',
        'Reusable patterns increase agency margins by 40%',
        'Accessibility-first toolkits eliminate post-launch remediation',
      ],
    ],
    [
      'id' => 'lab_marketplace',
      'sort_order' => 4,
      'title' => 'Template Marketplace Initiative',
      'tagline' => 'Premium starts. Infinite outcomes.',
      'track_id' => 'community',
      'description' => 'A path from a handful of great starting points to a thriving marketplace of beautiful, high-quality templates so ambition begins at day one, not after a $50k design cycle.',
      'shot_code' => 'GLASS_PANES_06',
      'signals' => [
        'Marketplace scale requires quality curation at every tier',
        'Day-one ambition drives long-term platform adoption',
        'Template ecosystems compound community investment over time',
      ],
    ],
    [
      'id' => 'lab_maintainers',
      'sort_order' => 5,
      'title' => 'Maintainers as Multipliers',
      'tagline' => 'Reduce friction. Increase trust.',
      'track_id' => 'community',
      'description' => 'Better tooling, clearer contribution pathways, and support systems so innovation compounds without burning out the people who keep the ecosystem healthy.',
      'shot_code' => 'PHYSICS_FINGERPRINTS_04',
      'signals' => [
        'Maintainer burnout is the #1 risk to open-source sustainability',
        'Clear contribution paths increase new contributor retention by 3×',
        'Tooling investment pays compound returns in ecosystem health',
      ],
    ],
  ],
  'sessions' => [
    [
      'id' => 's01',
      'title' => 'Opening Sequence: VISION 25',
      'type' => 'Keynote',
      'start' => '09:00',
      'end' => '09:15',
      'room_id' => 'atrium',
      'track_id' => 'product',
      'lab_id' => 'lab_canvas',
      'summary' => 'A cinematic opener that frames the summit as a forward-looking design + systems briefing.',
      'shot_code' => 'BLUEHOUR_BEAMS_02',
    ],
    [
      'id' => 's02',
      'title' => 'The Baseline of Ambition Has Shifted',
      'type' => 'Keynote',
      'start' => '09:20',
      'end' => '10:00',
      'room_id' => 'atrium',
      'track_id' => 'agency',
      'lab_id' => 'lab_agency',
      'summary' => 'What used to take weeks is now instantaneous. The new advantage is coherence, governance, and team-scale operation.',
      'shot_code' => 'OPTICS_REFRACTION_01',
    ],
    [
      'id' => 's03',
      'title' => 'From Content to Context',
      'type' => 'Talk',
      'start' => '10:15',
      'end' => '10:45',
      'room_id' => 'studio',
      'track_id' => 'product',
      'lab_id' => 'lab_context',
      'summary' => 'How structured context turns AI from a novelty into a trusted capability inside enterprise workflows.',
      'shot_code' => 'GLASS_PANES_03',
    ],
    [
      'id' => 's04',
      'title' => 'Composable Canvas Systems',
      'type' => 'Workshop',
      'start' => '10:55',
      'end' => '11:35',
      'room_id' => 'lab01',
      'track_id' => 'product',
      'lab_id' => 'lab_canvas',
      'summary' => 'Break premium layouts into reusable building blocks without breaking the art direction.',
      'shot_code' => 'GLASS_PANES_04',
    ],
    [
      'id' => 's05',
      'title' => 'Governance for AI-Assisted Creation',
      'type' => 'Talk',
      'start' => '11:45',
      'end' => '12:15',
      'room_id' => 'studio',
      'track_id' => 'product',
      'lab_id' => 'lab_governance',
      'summary' => 'Workflows, permissions, and review patterns that keep speed from becoming chaos.',
      'shot_code' => 'PHYSICS_FINGERPRINTS_02',
    ],
    [
      'id' => 's06',
      'title' => 'Break / Ambient Interlude',
      'type' => 'Interlude',
      'start' => '12:15',
      'end' => '13:00',
      'room_id' => 'atrium',
      'track_id' => 'community',
      'lab_id' => 'lab_marketplace',
      'summary' => 'A quiet reset: blue-hour beams, refractions, and tactile analog textures.',
      'shot_code' => 'EP01_BROLL_06',
    ],
    [
      'id' => 's07',
      'title' => 'From Builds to Systems',
      'type' => 'Talk',
      'start' => '13:00',
      'end' => '13:35',
      'room_id' => 'atrium',
      'track_id' => 'agency',
      'lab_id' => 'lab_agency',
      'summary' => 'How agencies stay premium when the front-end baseline becomes commoditized.',
      'shot_code' => 'BLUEHOUR_BEAMS_03',
    ],
    [
      'id' => 's08',
      'title' => 'Pricing the Age of Autocomplete',
      'type' => 'Panel',
      'start' => '13:45',
      'end' => '14:25',
      'room_id' => 'studio',
      'track_id' => 'agency',
      'lab_id' => 'lab_agency',
      'summary' => 'Shift value from hours to outcomes: accessibility, extensibility, governance, and long-term operations.',
      'shot_code' => 'HOOK_SET4_03',
    ],
    [
      'id' => 's09',
      'title' => 'From Five Great Starts to a Marketplace',
      'type' => 'Talk',
      'start' => '14:35',
      'end' => '15:05',
      'room_id' => 'atrium',
      'track_id' => 'community',
      'lab_id' => 'lab_marketplace',
      'summary' => 'A blueprint for scaling premium templates without sacrificing quality and trust.',
      'shot_code' => 'GLASS_PANES_05',
    ],
    [
      'id' => 's10',
      'title' => 'Maintainers as Multipliers',
      'type' => 'Workshop',
      'start' => '15:15',
      'end' => '15:55',
      'room_id' => 'lab02',
      'track_id' => 'community',
      'lab_id' => 'lab_maintainers',
      'summary' => 'Reduce friction, increase clarity, and help contributions compound sustainably.',
      'shot_code' => 'PHYSICS_FINGERPRINTS_03',
    ],
    [
      'id' => 's11',
      'title' => 'The Drupal Difference: Beautiful, But Real',
      'type' => 'Demo',
      'start' => '16:05',
      'end' => '16:35',
      'room_id' => 'atrium',
      'track_id' => 'product',
      'lab_id' => 'lab_canvas',
      'summary' => 'A design that looks like a one-off masterpiece, revealed as a clean, reusable system underneath.',
      'shot_code' => 'OPTICS_REFRACTION_02',
    ],
    [
      'id' => 's12',
      'title' => 'Closing Keynote: Restabilize Higher',
      'type' => 'Keynote',
      'start' => '16:45',
      'end' => '17:15',
      'room_id' => 'atrium',
      'track_id' => 'community',
      'lab_id' => 'lab_marketplace',
      'summary' => 'A forward-facing close: the triangle returns at a higher level, product, agencies, and community stepping up together.',
      'shot_code' => 'BLUEHOUR_BEAMS_04',
    ],
  ],
  'milestones' => [
    ['id' => 'past_2001', 'era' => 'past', 'year' => '2001', 'title' => 'A small tool becomes a platform', 'shot_code' => 'EP01_BROLL_01'],
    ['id' => 'past_2006', 'era' => 'past', 'year' => '2006', 'title' => 'Ecosystem momentum', 'shot_code' => 'PHYSICS_FINGERPRINTS_01'],
    ['id' => 'past_2011', 'era' => 'past', 'year' => '2011', 'title' => 'Structured content takes hold', 'shot_code' => 'EP01_BROLL_02'],
    ['id' => 'past_2016', 'era' => 'past', 'year' => '2016', 'title' => 'Modern experiences accelerate', 'shot_code' => 'HOOK_SET4_01'],
    ['id' => 'past_2021', 'era' => 'past', 'year' => '2021', 'title' => 'Composable thinking goes mainstream', 'shot_code' => 'EP01_BROLL_03'],
    ['id' => 'future_2026', 'era' => 'future', 'year' => '2026', 'title' => 'AI-native: content + context', 'shot_code' => 'OPTICS_REFRACTION_04'],
    ['id' => 'future_2030', 'era' => 'future', 'year' => '2030', 'title' => 'Governed experience fabric', 'shot_code' => 'GLASS_PANES_01'],
    ['id' => 'future_2035', 'era' => 'future', 'year' => '2035', 'title' => 'Trusted agents in production', 'shot_code' => 'BLUEHOUR_BEAMS_05'],
    ['id' => 'future_2042', 'era' => 'future', 'year' => '2042', 'title' => 'Marketplace maturity', 'shot_code' => 'GLASS_PANES_07'],
    ['id' => 'future_2051', 'era' => 'future', 'year' => '2051', 'title' => 'Drupal 2051 Charter', 'shot_code' => 'OPTICS_REFRACTION_05'],
  ],
  'pages' => [
    [
      'title' => 'Home',
      'description' => 'The baseline of ambition shifted. We step it up.',
      'components' => [
        [
          'component_id' => 'sdc.vision25.vision25-hero',
          'inputs' => [
            'hero_eyebrow' => 'Drupal 2051 — The Enterprise Innovation Summit',
            'hero_title' => 'VISION 25',
            'hero_subtitle' => 'The baseline of ambition shifted. We step it up.',
            'hero_cta_label' => 'Register Now',
            'hero_cta_url' => '/register',
            'image_url' => $theme_image('bluehour-beams-001.png'),
            'image_alt' => 'VISION 25',
          ],
        ],
        [
          'component_id' => 'block.vision25_home_ecosystem',
          'inputs' => [
            'ecosystem_label' => 'The Ecosystem',
            'ecosystem_title' => 'Three corners. One system.',
            'ecosystem_subtitle' => 'Product, agencies, and community — each strengthening the others.',
          ],
        ],
        [
          'component_id' => 'sdc.vision25.vision25-media-interlude',
          'inputs' => [
            'image_url' => $theme_image('bluehour-beams-011.png'),
            'image_alt' => '',
          ],
        ],
        [
          'component_id' => 'sdc.vision25.vision25-narrative-section',
          'inputs' => [
            'section_label' => 'Act I',
            'section_title' => 'The Flywheel',
            'body_primary' => 'For twenty-five years, Drupal has operated as a reinforcing system. The product empowers agencies. Agencies serve organizations. Organizations fund the community. The community improves the product.',
            'body_secondary' => "This isn't a supply chain. It's a flywheel — and every corner accelerates the others.",
            'background_variant' => 'default',
          ],
        ],
        [
          'component_id' => 'sdc.vision25.vision25-media-interlude',
          'inputs' => [
            'image_url' => $theme_image('bluehour-beams-054.png'),
            'image_alt' => '',
          ],
        ],
        [
          'component_id' => 'sdc.vision25.vision25-narrative-section',
          'inputs' => [
            'section_label' => 'Act II',
            'section_title' => 'Disruption',
            'body_primary' => 'AI disrupts all three corners simultaneously. Content creation is instant. Design is commoditized. The front-end baseline shifts overnight.',
            'body_secondary' => "This is not a crisis — it's a recalibration. The question is no longer \"can we build it?\" but \"can we govern it, scale it, and trust it?\"",
            'background_variant' => 'luminous',
          ],
        ],
        [
          'component_id' => 'sdc.vision25.vision25-media-interlude',
          'inputs' => [
            'image_url' => $theme_image('bluehour-beams-040.png'),
            'image_alt' => '',
          ],
        ],
        [
          'component_id' => 'sdc.vision25.vision25-narrative-section',
          'inputs' => [
            'section_label' => 'Act III',
            'section_title' => 'Restabilize Higher',
            'body_primary' => "The new operating model isn't about doing more — it's about operating at a higher level. Governed systems. Structured context. Composable experiences. Trusted agents.",
            'body_secondary' => 'A real system matters because beautiful surfaces without structure are dead ends. The triangle returns — elevated, coherent, and ready.',
            'background_variant' => 'default',
          ],
        ],
        [
          'component_id' => 'sdc.vision25.vision25-cta-band',
          'inputs' => [
            'title' => 'Step it up.',
            'subtitle' => 'The baseline of ambition shifted. Join us.',
            'cta_label' => 'Register Now',
            'cta_url' => '/register',
          ],
        ],
      ],
      'alias' => '/home',
      'is_front' => TRUE,
      'menu_title' => 'Home',
      'menu_path' => '/',
      'menu_weight' => 0,
    ],
    [
      'title' => 'Agenda',
      'description' => 'A single day. Three tracks. One shared ambition.',
      'components' => [
        [
          'component_id' => 'sdc.vision25.vision25-section-intro',
          'inputs' => [
            'page_label' => 'Schedule',
            'title' => 'Agenda',
            'subtitle' => 'A single day. Three tracks. One shared ambition.',
            'layout' => 'stack',
          ],
        ],
        [
          'component_id' => 'block.vision25_agenda_schedule',
        ],
      ],
      'alias' => '/agenda',
      'is_front' => FALSE,
      'menu_title' => 'Agenda',
      'menu_path' => '/agenda',
      'menu_weight' => 1,
    ],
    [
      'title' => 'Tracks',
      'description' => 'Three corners. One system.',
      'components' => [
        [
          'component_id' => 'block.vision25_track_section',
          'inputs' => [
            'track_id' => 'product',
          ],
        ],
        [
          'component_id' => 'block.vision25_track_section',
          'inputs' => [
            'track_id' => 'agency',
          ],
        ],
        [
          'component_id' => 'block.vision25_track_section',
          'inputs' => [
            'track_id' => 'community',
          ],
        ],
      ],
      'alias' => '/tracks',
      'is_front' => FALSE,
      'menu_title' => 'Tracks',
      'menu_path' => '/tracks',
      'menu_weight' => 2,
    ],
    [
      'title' => 'Labs',
      'description' => 'Six focused explorations. Each one a system waiting to scale.',
      'components' => [
        [
          'component_id' => 'sdc.vision25.vision25-section-intro',
          'inputs' => [
            'page_label' => 'Innovation Labs',
            'title' => 'Labs',
            'subtitle' => 'Six focused explorations. Each one a system waiting to scale.',
            'layout' => 'stack',
          ],
        ],
        [
          'component_id' => 'block.vision25_labs_grid',
        ],
      ],
      'alias' => '/labs',
      'is_front' => FALSE,
      'menu_title' => 'Labs',
      'menu_path' => '/labs',
      'menu_weight' => 3,
    ],
    [
      'title' => 'Timeline',
      'description' => 'From a small tool to a charter for 2051.',
      'components' => [
        [
          'component_id' => 'sdc.vision25.vision25-section-intro',
          'inputs' => [
            'page_label' => '25 Years Back. 25 Years Forward.',
            'title' => 'Timeline',
            'subtitle' => 'From a small tool to a charter for 2051.',
            'layout' => 'stack',
          ],
        ],
        [
          'component_id' => 'block.vision25_timeline_explorer',
          'inputs' => [
            'past_label' => 'Past 25',
            'future_label' => 'Next 25',
          ],
        ],
      ],
      'alias' => '/timeline',
      'is_front' => FALSE,
      'menu_title' => 'Timeline',
      'menu_path' => '/timeline',
      'menu_weight' => 4,
    ],
    [
      'title' => 'Register',
      'description' => 'Reserve your place at VISION 25.',
      'components' => [
        [
          'component_id' => 'sdc.vision25.vision25-section-intro',
          'inputs' => [
            'page_label' => 'Join Us',
            'title' => 'Register',
            'subtitle' => 'Reserve your place at VISION 25.',
            'layout' => 'register',
          ],
        ],
        [
          'component_id' => 'block.vision25_register_form',
          'inputs' => [
            'note' => 'Your information is handled with care. This is a concept demo — no data is stored or transmitted.',
          ],
        ],
      ],
      'alias' => '/register',
      'is_front' => FALSE,
      'menu_title' => 'Register',
      'menu_path' => '/register',
      'menu_weight' => 5,
    ],
    [
      'title' => 'System',
      'description' => 'Beautiful, but real.',
      'components' => [
        [
          'component_id' => 'sdc.vision25.vision25-section-intro',
          'inputs' => [
            'page_label' => 'Hidden Page',
            'title' => 'The Drupal Difference',
            'subtitle' => 'Beautiful, but real.',
            'layout' => 'stack',
          ],
        ],
        [
          'component_id' => 'sdc.vision25.vision25-system-explorer',
          'inputs' => [
            'visitor_button_label' => 'Visitor View',
            'system_button_label' => 'System View',
            'visitor_hero_label' => 'Hero_Cinematic',
            'visitor_header_label' => 'Section_Header',
            'visitor_triangle_label' => 'Triangle_Track_Nav',
            'visitor_bento_card_one_label' => 'Session_Card',
            'visitor_bento_card_two_label' => 'Lab_Card',
            'visitor_bento_card_three_label' => 'Session_Card',
            'visitor_cta_label' => 'CTA_Band',
            'visitor_timeline_label' => 'Timeline_Rail',
            'box_hero_label' => 'Hero_Cinematic',
            'box_header_label' => 'Section_Header',
            'box_triangle_label' => 'Triangle_Track_Nav',
            'box_bento_label' => 'Bento_Agenda_View',
            'box_cta_label' => 'CTA_Band',
            'box_timeline_label' => 'Timeline_Rail',
          ],
        ],
        [
          'component_id' => 'sdc.vision25.vision25-content-model',
          'inputs' => [
            'content_model_title' => 'Content Model',
            'quote' => 'A beautiful flat site is a dead end. A structured system scales for teams.',
            'relationship_1_from' => 'Labs',
            'relationship_1_label' => 'referenced by',
            'relationship_1_to' => 'Sessions',
            'relationship_2_from' => 'Sessions',
            'relationship_2_label' => 'categorized by',
            'relationship_2_to' => 'Tracks',
            'relationship_3_from' => 'Milestones',
            'relationship_3_label' => 'tagged Past / Future',
            'relationship_3_to' => 'Era',
          ],
        ],
      ],
      'alias' => '/system',
      'is_front' => FALSE,
      'menu_title' => NULL,
      'menu_path' => NULL,
    ],
  ],
];

if (!function_exists('parseFilter')) {
  function parseFilter($value): ?array {
    if ($value === FALSE || trim($value) === '') {
      return NULL;
    }

    return array_values(array_filter(array_map('trim', explode(',', $value))));
  }
}

if (!function_exists('filterByKey')) {
  function filterByKey(array $items, ?array $allowed, string $key): array {
    if ($allowed === NULL) {
      return $items;
    }

    return array_values(array_filter(
      $items,
      static fn(array $item): bool => in_array((string) ($item[$key] ?? ''), $allowed, TRUE),
    ));
  }
}

$filters = [
  'tracks' => parseFilter(getenv('VISION25_TRACK_IDS')),
  'rooms' => parseFilter(getenv('VISION25_ROOM_IDS')),
  'labs' => parseFilter(getenv('VISION25_LAB_IDS')),
  'sessions' => parseFilter(getenv('VISION25_SESSION_IDS')),
  'milestones' => parseFilter(getenv('VISION25_MILESTONE_IDS')),
  'pages' => parseFilter(getenv('VISION25_PAGE_TITLES')),
];

$data['tracks'] = filterByKey($data['tracks'], $filters['tracks'], 'id');
$data['rooms'] = filterByKey($data['rooms'], $filters['rooms'], 'id');
$data['labs'] = filterByKey($data['labs'], $filters['labs'], 'id');
$data['sessions'] = filterByKey($data['sessions'], $filters['sessions'], 'id');
$data['milestones'] = filterByKey($data['milestones'], $filters['milestones'], 'id');
$data['pages'] = filterByKey($data['pages'], $filters['pages'], 'title');

return $data;
