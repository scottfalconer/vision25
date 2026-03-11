# Vision 25 Drupal Build Artifact

This repository contains the Drupal build artifact for the Vision 25 site rebuild.

Included here:

- Drupal CMS composer project
- exported Drupal config in `config/sync/`
- custom module code in `web/modules/custom/`
- custom theme code in `web/themes/custom/`
- provisioning scripts in `scripts/vision25/`
- local DDEV config for running the site

Not included in this first pass:

- project docs
- parity evidence
- demo artifacts
- agent guidance files
- local runtime output, backups, and secrets

## Quick Start

```bash
ddev start
ddev composer install
ddev composer drupal:recipe-unpack
ddev drush site:install drupal_cms_installer -y \
  --account-name=admin \
  --account-pass=admin \
  --account-mail=hello@vision25.demo \
  --site-name='VISION 25'
ddev drush cim -y
ddev drush cr
VISION25_SEED_MODERATION_STATE=published ddev drush php:script scripts/vision25/provision.php
ddev drush cr
```

Default local URL:

- `https://vision25.ddev.site:8443/`
