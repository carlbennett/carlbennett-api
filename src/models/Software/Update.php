<?php

namespace CarlBennett\API\Models\Software;

use \CarlBennett\API\Libraries\Model;

class Update extends Model {

  public $http_code;
  public $result;

  public function __construct() {
    parent::__construct();
    $this->http_code = 500;
    $this->result    = null;
  }

}
