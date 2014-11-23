<?php

namespace CarlBennett\API\Libraries\Exceptions;

class UnspecifiedViewException extends APIException {

  public function __construct($prev_ex = null) {
    parent::__construct("Cannot respond with adequate view to request", 6, $prev_ex);
  }

}
