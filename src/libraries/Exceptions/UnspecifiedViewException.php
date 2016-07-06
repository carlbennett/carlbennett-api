<?php

namespace CarlBennett\API\Libraries\Exceptions;

use \CarlBennett\API\Libraries\Exceptions\APIException;
use \Exception;

class UnspecifiedViewException extends APIException {

  public function __construct(Exception $prev_ex = null) {
    parent::__construct("Cannot respond with adequate view to request", 4, $prev_ex);
  }

}
