# Global Codex working agreements

- Never claim tests passed unless you actually ran them and recorded the exact command(s) and result.
- Keep diffs small and scoped to the request.
- Prefer adding/adjusting tests when fixing bugs.
- Before destructive operations, stop and ask.
- When in doubt, propose a plan first, then implement.

## Visual parity (required for UI migrations)

If the request is a UI migration/rebuild with parity expectations:

- This is a **HARD GATE**: do not claim the migration is complete until **near-absolute visual parity** is achieved.
  - “Near-absolute parity” means: when comparing Source vs Drupal screenshots (desktop + mobile) for the agreed key routes, a human reviewer would reasonably say **“these are the same page”**.
  - Small pixel-level differences are acceptable, but layout, spacing rhythm, typography scale/weight, colors, and component styling must be visually equivalent.
  - Any intentional deviations must be explicitly documented in `docs/PARITY.md` and approved.
- Create `docs/PARITY.md` **before** implementation with: routes list, viewport sizes, and interactive flows to test.
- Capture and commit screenshots for each key route on:
  - Desktop (e.g. `1440×900`) and Mobile (e.g. `390×844`)
  - Source site AND Drupal site
  - Save under `docs/evidence/parity/<tag>/` where `<tag>` is a date or date+iteration (example: `2026-02-28-9`)
- Exercise and record results for key flows (at minimum): navigation menus, each form (error + success), and 404.
- Validate **site shell parity** on every key route: header branding, nav active-state behavior, breadcrumb presence/placement, and footer information architecture.
- Validate **link/CTA intent parity**: primary CTAs must preserve source intent (route change vs form submit vs download) and must not degrade into accidental self-links.
- Prefer automating capture with Playwright so it’s re-runnable; keep raw Playwright artifacts under `output/playwright/`.
- **Parity-first workflow (required)**:
  - Capture baseline evidence early, then iterate in small changes with frequent re-captures.
  - If parity is slipping (large diffs), stop adding new features/sections and fix parity first.
  - Do not report progress based only on functional correctness; parity evidence is the deliverable.
- Do not mark the task “done” until parity evidence + checklist is committed and referenced in `docs/MIGRATION.md`.

### Parity evidence hygiene (required)

Treat parity screenshots like tests: they must be repeatable and low-noise.

- **Normalize/disable screenshot noise** during automated capture: cookie banners, chat widgets, A/B banners, “builder/editor” badges, consent modals, debug toolbars/overlays.
  - If anything is hidden/normalized, **document it** in `docs/PARITY.md` so reviewers know what was changed for evidence.
  - Keep the suppression rules as explicit, reusable selectors in capture scripts so reruns are deterministic.
- **Freeze or explicitly allow dynamic content**:
  - Prefer freezing time/locale/timezone for capture (timestamps, countdowns, “last updated”, relative times).
  - If freezing is not feasible, document expected deltas as “acceptable differences” in `docs/PARITY.md`.
- **Disable animations/transitions** for capture to reduce flakes (CSS animations, shimmer skeletons, carousels).
- **Control the capture environment**: same browser, same viewport, same device scale factor, same color scheme, and consistent `prefers-reduced-motion` / `prefers-contrast`.
- **Avoid stale output when iterating**:
  - Clear Drupal caches after Twig/CSS/JS changes (`drush cr`), and ensure Playwright uses a fresh browser context or cache-busting query params between iterations.

### Parity debugging tactics (recommended)

- Debug with measurements, not vibes:
  - Use bounding boxes and computed styles (font family/size/weight, line-height, letter-spacing, colors, border radius, shadows).
  - Verify font files + weights load (missing weights cause faux-bold/fallback diffs).
  - Check rendered states (hover/focus-visible/active/disabled). Focus rings and ring-offsets frequently cause “why is this different?” diffs.
- If CSS overrides don’t “stick”, inspect the cascade:
  - Drupal core/contrib CSS may be unlayered and outrank layered utility CSS; use higher specificity or intentional unlayered overrides (and keep exceptions documented/minimal).
- For responsive bugs, always validate:
  - Breakpoint logic (where the layout switches) and container constraints (flex shrink/grow, min-width, max-width).
  - Responsive images (`srcset` + `sizes` + rendered box size). A wrong `sizes` value can make a “correct” image appear tiny on mobile.
  - Drupal image styles/derivatives (crop/quality/aspect). Tune styles to match the source; if you can’t, document the delta explicitly.

## Content architecture

This is a Drupal site. Drupal's core value is structured content — queryable, reusable, channel-independent data. Every content modeling decision should preserve that.

### Entity strategy

Use the right entity type for the job:

| Entity type | Use for | Structured fields? | Visual layout? |
|---|---|---|---|
| **Node** (with Canvas content template) | Any content that should be queryable, listable, or reusable across contexts — campaigns, events, reports, landing pages with typed metadata | Yes | Canvas content template |
| **Canvas page** | One-off designed pages where cross-page querying is not needed — homepage, 404, custom error pages | No (title + description only) | Canvas components |
| **Node** (standard rendering) | Simple utility content — privacy policy, terms of service, plain static pages | Yes | Drupal field formatters |

**Default to nodes with Canvas content templates** for any content that an editor might want to list, filter, search, or expose to other channels. Only use standalone `canvas_page` entities for true one-offs.

### Structured content rules

- **Node fields are canonical; Canvas templates map fields → components.** If a value should be queryable, listable, reusable, or exposed to other channels (headlines, dates, categories, descriptions, images), model it as node fields and map it into Canvas via the content template. Use component props for presentation-only concerns or page-specific overrides, and avoid duplicating canonical content in both places.
- **Never model queryable differences as hardcoded logic.** If pages behave differently based on type (event vs report vs demo), that type must be a field value editors can set, not a `match` statement or URL-alias lookup in PHP.
- **Use taxonomy for anything that groups or filters content.** Campaign types, topics, audience segments — these are vocabularies, not string fields.
- **Fields are for structured data. Components are for presentation.** A campaign's event date belongs in a date field on the node. The countdown timer component reads from that field via the Canvas content template. This keeps the data queryable and the presentation flexible.

### Field standards

- **Cardinality must match intent.** Single-value fields get cardinality `1`. Only set unlimited when editors genuinely need multiple values.
- **Restrict text formats** to the most appropriate format for each field. Short summaries get `plain_text` or `basic_html`. Rich body content gets `content_format`. Never leave `allowed_formats` empty.
- **Every field must have a description** that tells editors what to enter, expected length or dimensions, and how the value is used on the site.
- **Use the link field's built-in title** for CTA/button text. Don't create separate label fields alongside link fields.

### Views and listings

- **Any content type that will have more than a handful of entries needs a View** with proper access control (`access content`, not `type: none`), pagination, and cache tags.
- **Create a dedicated pathauto pattern** for each node content type. Don't rely on catch-all patterns or manually created aliases.

### Content templates vs standalone Canvas pages

When deciding whether new content should be a Canvas page or a node with a Canvas content template, ask:

1. Will there ever be more than one of these? → **Node**
2. Does any data on this page need to appear in a listing, feed, or API? → **Node**
3. Would an editor need to filter or search across pages of this type? → **Node**
4. Is this a unique, one-off designed page with no structured data needs? → **Canvas page**

If the answer to questions 1-3 is “not today but maybe later,” default to a node. Migrating from `canvas_page` to a node content type later is more work than starting with the right entity.

### Reusable migration lessons

- Keep source section boundaries in the mapping plan and preserve them in distinct template regions instead of collapsing them into one catch-all rich-text field.
- Make source JSON arrays deterministic before rendering (stable heading, order, and fallback behavior) so Canvas templates can stay mostly structural.
- Treat visual parity as deliverable code: baseline screenshot + each iterative tag should have explicit route list, viewport matrix, and deviation notes.
- Default to reusable node-driven layouts for repeatable page families and keep route exceptions (`canvas_page`) explicit.
- When enabling Canvas rendering for a node bundle, avoid self-recursive rendering paths: if Canvas template output renders the same node again, use a dedicated non-Canvas view mode (for example `canvas_legacy`) and render through that mode.
- Keep editorial copy canonical in node fields and map those fields into Canvas templates; do not hardcode hero/section text in Twig. Example from this project: home hero subhead is `field_intro_text` on the `vision_page` node (`Manifesto`).
- For source parity with Framer Motion-style hero intros, trigger animation start explicitly after Drupal behaviors attach (for example `data-hero-intro` + `is-visible`) rather than relying on immediate page-load CSS.
- For animated SVG line-draw effects, compute real segment lengths (`SVGGeometryElement.getTotalLength()`) and bind them to CSS variables (`--vision-line-length`) instead of hardcoded dash lengths.
- Implement a reusable reveal contract (`data-scroll-reveal`, optional delay attribute, `IntersectionObserver` once behavior with source-equivalent viewport margin) and apply it consistently across all route variants, not only the homepage.
- Keep parity capture and animation verification separate: run screenshot parity with reduced motion for deterministic diffs, and run a dedicated motion probe with `reducedMotion: no-preference` to assert animation behavior.
- Before calling a migration "editor-ready," explicitly verify role permissions for every custom bundle (`create/edit/delete/revisions`) and for any text formats used by those fields (for this project, `basic_html` is required by core Vision copy fields).
- Treat hardcoded theme navigation as temporary at most; production nav should be menu-driven (`main` or project-specific menu) so site builders can manage labels/order/links without code changes.
- For repeatable migrated bundles, provide at least one dedicated View (with `access content`, pagination, and cache tags) rather than relying only on custom preprocess aggregation or generic `/admin/content` filtering.
- If using the Canvas "legacy render block" bridge pattern (`canvas.content_template -> legacy block -> non-Canvas view mode`), document it as an explicit transitional architecture and track a follow-up to replace with true Canvas component composition.
- When decomposing a Canvas page from legacy blocks to SDCs, keep the composition migration deterministic and source-controlled. Derive component placement from config/seed definitions or update hooks; do not rely on manual Canvas editor recomposition as the implementation path.
- Before deleting legacy Canvas block plugins or their config, verify that no active `canvas_page` component trees still reference those plugin IDs. Remove runtime references first, then delete plugin code/config in the same reviewed change.
- Audit URL patterns for collisions between node aliases and Canvas pages (example risk: `vision_page` alias token `/[field_layout_variant]` overlapping a standalone Canvas page route like `/home`).
- Be careful with config workflow order during remediation: if sync files are edited manually, run `drush cim` to apply; running `drush cex` first will overwrite sync edits from active config.
- Treat Canvas editor preview as its own rendering environment. If a component needs preview-only fixes, scope them explicitly to Canvas/admin routes (for example `.path-canvas`) and verify that the public site output remains unchanged.
- For public or shared repos, strip local-only artifacts before handoff: temp probe scripts/files, `.DS_Store`, local checkpoint pointers, `output/`, `backups/`, and generated `sites/default/files` state. Demo automation must read credentials from environment variables rather than committed defaults.

### Editor and site-builder review checklist (run before signoff)

- Confirm editors can create/edit/revise every migrated bundle and can use every text format required by those fields.
- Ensure visible copy is editable from obvious fields with help text (for example: hero year, hero headline, intro/subhead) and not buried in Twig or JS.
- Keep navigation menu-driven and editable in Drupal UI; avoid path/alias-based hardcoded nav logic except as temporary fallback.
- Provide at least one dedicated View for each repeatable bundle and verify access, pagination, and cache tags.
- Hide migration-only technical fields from editor forms by default (`source_id`, sort/debug fields) unless there is an explicit operational need.
- Prefer Media Library widgets over raw entity autocomplete for editor-facing media fields.
- Treat Canvas legacy bridge rendering as temporary; track and prioritize migration to true Canvas component composition.
- Validate motion parity separately from static screenshot parity (hero intro pop-in, triangle line movement, scroll reveals) with dedicated evidence.
- Run a quick URL collision audit for node aliases vs standalone Canvas pages before publishing routing changes.

---

# Drupal Site Build Fundamentals

Use this as a baseline policy for Drupal 10/11 and Drupal CMS projects. Keep it tool-agnostic and project-agnostic.

## Purpose

Guide an agent to deliver a Drupal site that is:

1. Reproducible from a clean checkout.
2. Secure by default.
3. Cache-correct and performant by default.
4. Accessible by default.
5. Maintainable through Drupal-native APIs and workflows.

## Agent Operational Boundaries

1. Deterministic enforcement:
   - Schema validation, permissions, workflow state, and access control are authoritative.
   - If an action fails, you MUST fix the payload/config and retry.
   - You MUST NOT bypass Drupal systems (no direct DB edits, no disabling validation, no “temporary” permission broadening).

2. Stable interfaces only:
   - You MUST use stable, automatable interfaces (CLI and HTTP APIs where applicable).
   - For site build, migration, and remediation work, you MUST NOT automate the Drupal admin UI via browser clicking/scraping or DOM-driven automation.
   - Exception: if the user explicitly asks for a demo or recording after the build is complete, browser-driven admin UI interaction is allowed on local/dev environments to produce the demo artifact. Treat that as presentation work, not source-of-truth build automation, and document any content/config state changes it creates.

3. Change classification and gating:
   - Content changes: Allowed only through entity APIs and MUST create revisions with clear revision log messages.
   - High-impact configuration changes (content model, workflows, permissions, text formats, site-wide behavior, Canvas-related configuration):
     MUST be delivered as reviewable diffs (exported config/recipes) and MUST NOT be silently changed in-place.
   - Code changes: MUST be delivered as a diff/PR and MUST NOT be applied directly to production environments.

4. Bulk operations:
   - Bulk writes MUST be batched, rate-limited, resumable, and have a rollback strategy.
   - You SHOULD use Drupal-native bulk mechanisms (e.g., queue/batch/migrate patterns) rather than one-off scripts.

5. Auditing:
   - All automated actions MUST be attributable to a dedicated, least-privileged actor.
   - You MUST capture command output/errors and link them to the change (commit, PR, or log artifact).

## Non-Negotiables

### Reproducibility

1. You MUST be able to build the site from scratch using documented steps.
2. You MUST NOT rely on manual-only UI changes as sole source of truth.
3. You MUST log exact commands run and outcomes, including failures.
4. You SHOULD use Drush as the standard Drupal operational CLI for install, config, cache, and maintenance tasks.

### Dependency and Code Ownership

1. You MUST manage PHP dependencies with Composer.
2. You MUST commit `composer.lock` when dependencies change.
3. You MUST NOT edit Drupal core, contrib, or `vendor/` directly.
4. You MUST implement customizations in custom modules/themes.
5. For contrib/core fixes, you SHOULD use a documented patch workflow.

### Configuration, Recipes, and Governance

1. Exported configuration (and recipe artifacts when used) MUST be treated as source of truth for site structure.
2. You MUST use a consistent config import/export workflow and document it.
3. High-impact architectural changes (content model, workflows, permissions, text formats, Canvas-related configuration) MUST be managed as reviewable diffs and MUST NOT be silently changed in-place.
4. Low-risk settings changes are allowed only if:
   - the actor has permission, and
   - the resulting state is still captured in the reproducible source of truth (exported config where applicable).
5. You MUST keep environment-specific values (secrets, API keys, per-environment endpoints) out of exported config.
6. You MUST define config vs content vs code ownership in project docs, including any Canvas-related artifacts that are required to reproduce the site.
7. You SHOULD place the config sync directory outside webroot when feasible.
8. Recipes are useful for initial scaffolding; plan ongoing changes via normal config management after install.
9. Provisioning scripts MUST be idempotent and MUST NOT introduce config drift (after a clean install + config import, running provisioning should not require a config export to reconcile changes).

### Drupal Architecture

1. Dependency Injection:
   - In OOP code, use constructor/container injection.
   - Avoid static container lookups in OOP classes except last-resort edge cases.
2. Render API:
   - Return render arrays from controllers/blocks/builders.
   - Do not concatenate raw HTML strings in PHP for page output.
   - In Twig, avoid `|raw`; only use it for trusted, already-sanitized markup with explicit justification.
3. Cacheability:
   - Dynamic output MUST declare correct cache contexts/tags/max-age.
   - Do not disable caching to hide logic issues.
4. Entity API:
   - You MUST NOT use raw SQL for entity reads/writes that would bypass validation, access checks, revisions, or cache metadata.
   - All entity reads/writes MUST pass through Drupal entity APIs (entity storage/query/etc.).
5. Routing and Access:
   - Protect routes via permissions/roles/custom access checks.
6. Updates:
   - Ship `hook_update_N()` for data/storage transitions on existing installs.
7. Translation:
   - User-facing strings in PHP/Twig MUST be translatable.
8. Hooks and extension points:
   - For Drupal 11.1+, new hook implementations SHOULD prefer class-based hooks where supported.
   - For Drupal 10/compatibility needs, procedural hooks in `.module` files remain acceptable.
   - Prefer hooks/events/service decoration over ad hoc overrides or monkey-patching.

## External UI-to-CMS Migration Rules

1. Mapping is required:
   - You MUST maintain a committed mapping specification describing how source pages/sections map to Drupal entities, fields, menus, and URL aliases/redirects.
   - The mapping MUST be deterministic (re-runnable without creating duplicates or drifting structure).
   - Section boundaries MUST be preserved in mapping. Do not collapse distinct source sections into one generic rich-text blob when separate components/fields are required for parity and editor control.

2. Separate content from presentation:
   - You MUST extract content atoms (text, links, images, structured lists) into typed Drupal structures.
   - You MUST NOT import raw frontend layout code (e.g., React/Tailwind/HTML wrappers) into rich text fields as the primary approach.
   - If HTML is imported at all, it MUST be sanitized and constrained to an approved text format.

3. Canvas page assembly:
   - Page layouts MUST be created using the project’s Canvas approach and component architecture.
   - Any Canvas-related artifacts that are required for the build MUST be reproducible from a clean checkout (config/recipes/code and documented steps).
   - Theme compatibility for Canvas is a build prerequisite; fail fast if the active theme is not Canvas-compatible.

4. Workflow state:
   - Imported content MUST default to a non-published workflow state.
   - Publishing MUST require an explicit permissioned transition and/or approval step.

5. Taxonomy and controlled values:
   - You MUST map to existing vocabularies/allowed values.
   - You MUST NOT create new terms/values during import unless an explicit allowlist/mapping rule permits it.

6. Media handling:
   - Images/files MUST be imported and managed via Drupal’s Media/File systems.
   - Alt text and attribution metadata MUST be preserved or created according to the content model.

7. URLs and redirects:
   - You MUST preserve or intentionally remap URLs.
   - If URLs change, you MUST provide redirects and document the rules.
   - Canonical URL behavior MUST be validated after import (including homepage path) so rendered canonical/shortlink output matches the intended route strategy.

### Security and Privacy

1. Keep Twig auto-escaping intact.
2. Use Drupal sanitization/escaping APIs for untrusted data.
3. Treat text formats as a security boundary.
4. Keep secrets out of Git and out of exported configuration.
5. Enforce least-privilege permissions.
6. Treat `|raw` in Twig as security-sensitive and exceptional.
7. Migration security:
   - Imported content is untrusted. Imported HTML MUST be sanitized and text formats MUST be enforced (no Full HTML by default).
   - Inline scripts/styles MUST NOT be imported into rich text fields.
   - You MUST NOT rely on arbitrary PHP/SQL execution (ad hoc eval/scripts/direct DB) as a substitute for reproducible code/config.
   - You MUST NOT add “admin convenience backdoors” (routes/controllers that bypass access checks).

### Frontend and JS

1. Manage CSS/JS via `*.libraries.yml` and attach intentionally.
2. Use Drupal JavaScript behaviors and `once()` for idempotent attachment.
3. Use `drupalSettings` for server-to-client runtime values.
4. Keep template logic minimal; move non-trivial logic to preprocess/services.
5. Preserve core Twig attributes variables on wrapper elements.
6. Always print `{{ attributes }}` on the outermost element of component templates (and related attribute variables such as `title_attributes` where applicable).

### Accessibility

1. Use semantic HTML first.
2. Ensure keyboard accessibility for interactive components.
3. Use ARIA only when semantic HTML is insufficient.
4. Target WCAG AA for user-facing experiences.

### Quality and Operations

1. You MUST run automated tests/checks before signoff and report results.
2. You SHOULD run Drupal coding standards checks for custom code.
3. You SHOULD use Drupal logging channels (PSR-3) for operational events.
4. You MUST ensure cron is configured and running.
5. If the work is a migration or has a “parity” requirement, you MUST validate:
   - Visual parity on key routes (at minimum: desktop + mobile viewports, and above-the-fold)
   - Interactive parity for key flows (forms submit/validation/success states, navigation, menus)
   Record evidence (screenshots and a short checklist of flows exercised).

## Definition of Done (Baseline)

1. Clean install + dependency install + config import/provision succeeds without manual patch-up.
2. Key routes load without fatal errors.
3. Access and permissions are correct for anonymous/authenticated/editor roles.
4. Caching behaves correctly (no stale or only-after-cache-clear behavior).
5. Automated tests/checks are executed and results recorded.
6. Documentation includes:
   - setup workflow
   - config workflow
   - update workflow
   - known intentional deviations
7. If parity is in scope, parity is explicitly verified (visual + interactive) and evidence is recorded.
   - Parity signoff is blocked if reproducible build gates fail (clean install, config import, migration replay, and no config drift).
8. If external migration is in scope:
   - The repo contains the mapping specification and documentation needed to reproduce the import results.
   - A clean install can run provisioning + config import + migration/import end-to-end without manual UI patch-up.
   - Imports are re-runnable and have a defined rollback/reset strategy.
   - Imported content lands in the correct non-published workflow state and can only be published via governed transitions.
   - No “raw frontend layout dump” exists in WYSIWYG fields as a substitute for structured modeling.
   - Canvas editing works with the chosen theme/component approach; if not, the build fails fast with a documented cause.

## Content Model Definition of Done (Add-On)

Use this for marketing/campaign sites and any build meant to be editor-maintainable.

1. Editors own visible copy and media via Drupal entities (nodes/blocks/media/config entities), not PHP/Twig hardcoding.
2. Layout/variant decisions are editor-selectable (field-driven, Layout Builder, Canvas), not hardcoded in theme PHP by alias/path/title.
3. Field storages use intentional cardinality (default `1` unless there is a clear multi-value editorial need).
4. Field help text is present for editor-facing fields (description/help), including expectations (length, dimensions, examples).
5. Text fields have restricted `allowed_formats` (no “any format” unless explicitly justified).
6. Exported config has no “ghost fields” referenced in displays (e.g., `body` in form/view display without a bundle field instance).
7. URL aliases are deterministic for the bundle (Pathauto pattern and selection criteria) unless explicitly managed another way.
8. Avoid redundant “label” fields when the primary field already supports it (example: use Link field title instead of separate CTA label field).

### Preflight Checks (Before Clean Build Signoff)

Run these quick scans from repo root and remediate any hits (unless explicitly justified and documented):

```bash
# Runtime legacy-content reads (theme/module PHP).
rg -n \"DRUPAL_ROOT.*\\./\\.\\./\\.\\./src|file_get_contents\\(.*src/|/src/content/\" drupal/web/modules drupal/web/themes

# Unlimited cardinality (cardinality -1) in exported config.
rg -n \"^cardinality:\\s*-1\\s*$\" drupal/config/sync/field.storage.*.yml

# Unrestricted text formats on text fields.
rg -n \"allowed_formats:\\s*\\{\\s*\\}\" drupal/config/sync/field.field.*.yml

# Ghost body field drift in displays.
rg -n \"^\\s+body:\\s*$\" drupal/config/sync/core.entity_(form|view)_display\\..*\\.yml

# Local-only artifacts that should not ship in a clean repo.
find . \\( -name '.DS_Store' -o -path './output' -o -path './backups' -o -path './.peer' -o -path './drupal/web/sites/default/files' \\) -prune -print
```

### Clean Build QA (Editor Readiness)

These checks are meant to catch “developer demo but not editor-usable” builds before handoff.

1. Clean build succeeds end-to-end (per docs) without manual UI patch-up.
2. After importing config, there is no drift:
   - `drush config:import -y`
   - `drush config:status` shows no differences
3. Key editorial workflows are usable:
   - Editors can create/edit the primary content types without seeing confusing controls (example: single-value fields should not show “Add another item”).
   - Text formats exposed to editors are intentionally restricted (avoid Full HTML unless explicitly required and permissions are scoped).
   - Pathauto generates expected aliases for the primary bundles (or aliases are otherwise deterministically managed).
   - Content moderation (if enabled) is correctly available to the intended roles, with least-privilege permissions.
4. For Canvas/Layout Builder builds:
   - Site builder can place/edit components/blocks without requiring code changes.
   - Component/page composition does not rely on hardcoded variant logic by path/title.
5. If the project has a parity requirement:
   - Capture and review screenshots for key routes in desktop + mobile viewports.
   - Exercise key interactive flows (especially forms) and confirm success/error states behave as intended.
