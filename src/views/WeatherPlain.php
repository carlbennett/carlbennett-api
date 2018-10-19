<?php

namespace CarlBennett\API\Views;

use \CarlBennett\API\Models\Weather as WeatherModel;
use \CarlBennett\MVC\Libraries\Common;
use \CarlBennett\MVC\Libraries\Exceptions\IncorrectModelException;
use \CarlBennett\MVC\Libraries\Model;
use \CarlBennett\MVC\Libraries\View;

class WeatherPlain extends View {
  public function getMimeType() {
    return 'text/plain;charset=utf-8';
  }

  public function render( Model &$model ) {
    if ( !$model instanceof WeatherModel ) {
      throw new IncorrectModelException();
    }

    $string = $model->weather_report->getAsPlain();

    if ( $string === false ) {
      echo "Error: unable to download report or location not given.\n";
    } else {
      echo $string;
    }
  }
}
