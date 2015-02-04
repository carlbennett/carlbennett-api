<?php

namespace CarlBennett\API\Controllers;

use CarlBennett\API\Libraries\Controller;
use CarlBennett\API\Libraries\Exceptions\UnspecifiedViewException;
use CarlBennett\API\Libraries\Router;

use CarlBennett\API\Models\Weather as WeatherModel;
use CarlBennett\API\Views\WeatherJSON as WeatherJSONView;
use CarlBennett\API\Views\WeatherPlain as WeatherPlainView;

class Weather extends Controller {

  public function run(Router &$router) {
    $query = $router->getRequestQueryArray();
    $location = (isset($query["location"]) ? $query["location"] : "");
    switch ($router->getRequestPathExtension()) {
      case "txt":
      case "text":
        $view = new WeatherPlainView();
        break;
      case "":
      case "json":
        $view = new WeatherJSONView();
      break;
      default:
        throw new UnspecifiedViewException();
    }
    $model = new WeatherModel($location);
    if (extension_loaded("newrelic")) {
      newrelic_add_custom_parameter("model", (new \ReflectionClass($model))->getShortName());
      newrelic_add_custom_parameter("view", (new \ReflectionClass($view))->getShortName());
    }
    ob_start();
    $view->render($model);
    $router->setResponseCode(200);
    $router->setResponseHeader("Cache-Control", "max-age=300");
    $router->setResponseHeader("Content-Type", $view->getMimeType());
    $router->setResponseHeader("Expires", (new \DateTime("+300 second"))->setTimezone(new \DateTimeZone("GMT"))->format("D, j M Y H:i:s e"));
    $router->setResponseHeader("Pragma", "max-age=300");
    $router->setResponseContent(ob_get_contents());
    ob_end_clean();
  }

}
