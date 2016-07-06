<?php

namespace CarlBennett\API\Views;

use \CarlBennett\API\Libraries\Common;
use \CarlBennett\API\Libraries\Exceptions\IncorrectModelException;
use \CarlBennett\API\Libraries\Model;
use \CarlBennett\API\Libraries\View;
use \CarlBennett\API\Models\Weather as WeatherModel;

class WeatherMarkdown extends View {

  public function getMimeType() {
    return "text/x-markdown;charset=utf-8";
  }

  public function render(Model &$model) {
    if (!$model instanceof WeatherModel) {
      throw new IncorrectModelException();
    }
    $string = $model->weather_report->getAsMarkdown();
    if ($string === false) {
      echo "**Error:** unable to download report or location not given.\n";
    } else {
      echo $string;
    }
  }

}
