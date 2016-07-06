<?php

namespace CarlBennett\API\Controllers;

use \CarlBennett\API\Libraries\Controller;
use \CarlBennett\API\Libraries\Exceptions\UnspecifiedViewException;
use \CarlBennett\API\Libraries\Router;
use \CarlBennett\API\Models\Maintenance as MaintenanceModel;
use \CarlBennett\API\Views\MaintenanceJSON as MaintenanceJSONView;
use \CarlBennett\API\Views\MaintenanceMarkdown as MaintenanceMarkdownView;
use \CarlBennett\API\Views\MaintenancePlain as MaintenancePlainView;

class Maintenance extends Controller {

  public function run(Router &$router) {
    switch ($router->getRequestPathExtension()) {
      case "json":
        $view = new MaintenanceJSONView();
      break;
      case "md":
        $view = new MaintenanceMarkdownView();
      break;
      case "txt": case "":
        $view = new MaintenancePlainView();
      break;
      default:
        throw new UnspecifiedViewException();
    }
    $model = new MaintenanceModel();
    ob_start();
    $view->render($model);
    $router->setResponseCode(503);
    $router->setResponseTTL(0);
    $router->setResponseHeader("Content-Type", $view->getMimeType());
    $router->setResponseContent(ob_get_contents());
    ob_end_clean();
  }

}
