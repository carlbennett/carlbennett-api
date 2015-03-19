<?php

namespace CarlBennett\API\Libraries\Exceptions;

class UnhandledInternalResponseException extends APIException {

  public function __construct($prev_ex = null) {
    parent::__construct("Unhandled internal response", 8, $prev_ex);
    $this->httpResponseCode = 500;
  }

}
