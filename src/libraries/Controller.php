<?php

namespace CarlBennett\API\Libraries;

use \CarlBennett\API\Libraries\Router;
use \CarlBennett\MVC\Libraries\Logger;

abstract class Controller {

  public function __construct() {
    Logger::logMetric("controller", get_class($this));
  }

  public abstract function run(Router &$router);

}
