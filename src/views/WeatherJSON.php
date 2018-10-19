<?php

namespace CarlBennett\API\Views;

use \CarlBennett\API\Models\Weather as WeatherModel;
use \CarlBennett\MVC\Libraries\Common;
use \CarlBennett\MVC\Libraries\Exceptions\IncorrectModelException;
use \CarlBennett\MVC\Libraries\Model;
use \CarlBennett\MVC\Libraries\View;

class WeatherJSON extends View {
  public function getMimeType() {
    return 'application/json;charset=utf-8';
  }

  public function render( Model &$model ) {
    if ( !$model instanceof WeatherModel ) {
      throw new IncorrectModelException();
    }
    echo json_encode(
      $model->weather_report->getAsObject(), Common::prettyJSONIfBrowser()
    );
  }
}
