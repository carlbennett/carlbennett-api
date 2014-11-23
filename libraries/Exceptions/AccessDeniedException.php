<?php

namespace CarlBennett\API\Libraries\Exceptions;

class AccessDeniedException extends APIException {

  public function __construct($prev_ex = null) {
    parent::__construct("Access denied", 4, $prev_ex);
    $this->httpResponseCode = 401;
  }

}
