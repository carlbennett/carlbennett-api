<?php

namespace CarlBennett\API\Libraries;

use \CarlBennett\API\Libraries\Logger;
use \CarlBennett\API\Libraries\Model;

abstract class View {

  public function __construct() {
    Logger::logMetric("view", get_class($this));
  }

  public abstract function getMimeType();
  public abstract function render(Model &$model);

}
