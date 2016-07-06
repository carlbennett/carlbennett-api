<?php

namespace CarlBennett\API\Libraries;

use \CarlBennett\API\Libraries\Logger;
use \CarlBennett\API\Libraries\Router;

abstract class Controller {

  public function __construct() {
    Logger::logMetric("controller", get_class($this));
  }

  public abstract function run(Router &$router);

}
