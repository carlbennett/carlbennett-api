<?php

namespace CarlBennett\API\Libraries\Exceptions;

use \CarlBennett\API\Libraries\Exceptions\APIException;
use \Exception;

class ClassNotFoundException extends APIException {

  public function __construct($className, Exception $prev_ex = null) {
    parent::__construct("Required class '$className' not found", 1, $prev_ex);
  }

}
