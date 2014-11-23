<?php

namespace CarlBennett\API\Libraries\Exceptions;

class IncorrectModelException extends APIException {

  public function __construct($extensionName, $prev_ex = null) {
    parent::__construct("Incorrect model provided to view", 5, $prev_ex);
  }

}
