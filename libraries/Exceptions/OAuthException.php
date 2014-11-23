<?php

namespace CarlBennett\API\Libraries\Exceptions;

class OAuthException extends APIException {

  public function __construct($message, $prev_ex = null) {
    parent::__construct($message, 7, $prev_ex);
  }

}
