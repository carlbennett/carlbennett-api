<?php

namespace CarlBennett\API\Libraries\Exceptions;

class ExtensionMissingException extends APIException {

  public function __construct($extensionName, $prev_ex = null) {
    parent::__construct("Required extension or library '$extensionName' not found", 2, $prev_ex);
  }

}
