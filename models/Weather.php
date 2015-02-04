<?php

namespace CarlBennett\API\Models;

use CarlBennett\API\Libraries\Model;
use CarlBennett\API\Libraries\WeatherReport;

class Weather extends Model {

  public $weather_report;

  public function __construct($location) {
    $this->weather_report = new WeatherReport($location);
  }

}
