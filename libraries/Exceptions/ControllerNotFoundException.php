<?php

namespace CarlBennett\API\Libraries\Exceptions;

use \CarlBennett\API\Libraries\Exceptions\APIException;
use \Exception;

class ControllerNotFoundException extends APIException {

  public function __construct($controllerName, Exception $prev_ex = null) {
    parent::__construct("Unable to find a suitable controller given the path", 2, $prev_ex);
    $this->httpResponseCode = 404;
  }

}
