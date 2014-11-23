<?php

namespace CarlBennett\API\Libraries\Exceptions;

abstract class APIException extends \Exception {

  protected $httpResponseCode = 500;

  public function getHTTPResponseCode() {
    return $this->httpResponseCode;
  }

}
