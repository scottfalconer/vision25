<?php

declare(strict_types=1);

namespace Drupal\vision25_site\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;

/**
 * Provides custom route controllers for the Vision 25 site.
 */
final class Vision25SiteController extends ControllerBase {

  /**
   * Builds the themed not-found route used by the custom 404 page.
   */
  public function notFound(): array {
    return [
      '#theme' => 'vision25_not_found_panel',
      '#front_page' => Url::fromRoute('<front>')->toString(),
    ];
  }

}
