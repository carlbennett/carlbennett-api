<?php

namespace CarlBennett\API\Libraries\Exceptions;

use \CarlBennett\API\Libraries\Exceptions\APIException;
use \Exception;

class SoftwareLicenseNotFoundException extends APIException {

  public function __construct($id, Exception $prev_ex = null) {
    parent::__construct('Software license not found: ' . $id, 6, $prev_ex);
    $this->httpResponseCode = 404;
  }

}
