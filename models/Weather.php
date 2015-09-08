<?php

namespace CarlBennett\API\Models;

use \CarlBennett\API\Libraries\Model;

class Weather extends Model {

  public $location;
  public $weather_report;

  public function __construct($location) {
    parent::__construct();
    $this->location       = $location;
    $this->weather_report = null;
  }

}
