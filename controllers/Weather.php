<?php

namespace CarlBennett\API\Controllers;

use \CarlBennett\API\Libraries\Controller;
use \CarlBennett\API\Libraries\Exceptions\UnspecifiedViewException;
use \CarlBennett\API\Libraries\Router;
use \CarlBennett\API\Libraries\WeatherReport;
use \CarlBennett\API\Models\Weather as WeatherModel;
use \CarlBennett\API\Views\WeatherJSON as WeatherJSONView;
use \CarlBennett\API\Views\WeatherPlain as WeatherPlainView;

class Weather extends Controller {

  public function run(Router &$router) {
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
    $query    = $router->getRequestQueryArray();
    $location = (isset($query["location"]) ? $query["location"] : "");
    $model    = new WeatherModel($location);
    $this->getWeatherReport($model);
    ob_start();
    $view->render($model);
    $router->setResponseCode((!isset($query["location"]) ? 400 : 200));
    $router->setResponseTTL(300);
    $router->setResponseHeader("Content-Type", $view->getMimeType());
    $router->setResponseContent(ob_get_contents());
    ob_end_clean();
  }

  protected function getWeatherReport(WeatherModel &$model) {
    $model->weather_report = new WeatherReport($model->location);
  }

}
