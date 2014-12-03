<?php

namespace CarlBennett\API\Libraries\Exceptions;

class MethodNotAllowedException extends APIException {

  public function __construct($allowed_methods, $prev_ex = null) {
    parent::__construct("HTTP method not allowed", 3, $prev_ex);
    $this->httpResponseCode = 405;
    $this->setHTTPResponseHeader("Allow", implode(",", $allowed_methods));
  }

}
