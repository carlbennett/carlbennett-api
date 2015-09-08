<?php

namespace CarlBennett\API\Libraries\Exceptions;

use \CarlBennett\API\Libraries\Exceptions\APIException;
use \Exception;

class MethodNotAllowedException extends APIException {

  public function __construct($allowed_methods, Exception $prev_ex = null) {
    parent::__construct("HTTP method not allowed", 5, $prev_ex);
    $this->httpResponseCode = 405;
    $this->setHTTPResponseHeader("Allow", implode(",", $allowed_methods));
  }

}
