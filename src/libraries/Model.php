<?php

namespace CarlBennett\API\Libraries;

use \CarlBennett\API\Libraries\Logger;

abstract class Model {

  public function __construct() {
    Logger::logMetric("model", get_class($this));
  }

}
