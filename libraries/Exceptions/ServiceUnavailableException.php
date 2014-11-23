<?php

namespace CarlBennett\API\Libraries\Exceptions;

class ServiceUnavailableException extends APIException {

  public function __construct($prev_ex = null) {
    parent::__construct("API service has been disabled", 1, $prev_ex);
    $this->httpResponseCode = 503;
  }

}
