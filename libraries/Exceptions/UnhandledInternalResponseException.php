<?php

namespace CarlBennett\API\Libraries\Exceptions;

use \CarlBennett\API\Libraries\Exceptions\APIException;
use \Exception;

class UnhandledInternalResponseException extends APIException {

  public function __construct(Exception $prev_ex = null) {
    parent::__construct("Unhandled internal response", 6, $prev_ex);
    $this->httpResponseCode = 500;
  }

}
