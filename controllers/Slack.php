<?php

namespace CarlBennett\API\Controllers;

use CarlBennett\API\Libraries\Controller;
use CarlBennett\API\Libraries\Exceptions\UnspecifiedViewException;
use CarlBennett\API\Libraries\Router;

use CarlBennett\API\Models\Slack as SlackModel;
use CarlBennett\API\Views\SlackMarkdown as SlackMarkdownView;

class Slack extends Controller {

  public function run(Router &$router) {
    $pathArray = $router->getRequestPathArray();
    $func = (isset($pathArray[2]) ? pathinfo($pathArray[2], PATHINFO_FILENAME) : "");
    $model = new SlackModel();
    switch ($func) {
      case "webhook":
        if (extension_loaded("newrelic")) {
          newrelic_name_transaction("/slack/webhook");
        }
        $model->webhook($router);
      break;
      default:
        throw new UnspecifiedModelFunctionException();
    }
    switch ($router->getRequestPathExtension()) {
      case "":
      case "md":
        $view = new SlackMarkdownView();
      break;
      default:
        throw new UnspecifiedViewException();
    }
    if (extension_loaded("newrelic")) {
      newrelic_add_custom_parameter("model", (new \ReflectionClass($model))->getShortName());
      newrelic_add_custom_parameter("view", (new \ReflectionClass($view))->getShortName());
    }
    ob_start();
    $view->render($model);
    $router->setResponseCode(200);
    $router->setResponseHeader("Cache-Control", "max-age=0,must-revalidate,no-cache,no-store");
    $router->setResponseHeader("Content-Type", $view->getMimeType());
    $router->setResponseHeader("Expires", "0");
    $router->setResponseHeader("Pragma", "max-age=0");
    $router->setResponseContent(ob_get_contents());
    ob_end_clean();
  }

}
