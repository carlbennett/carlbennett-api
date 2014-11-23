<?php

namespace CarlBennett\API\Libraries;

use CarlBennett\API\Libraries\Router;

abstract class Controller {

  public abstract function run(Router &$router);

}
