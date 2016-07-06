<?php

namespace CarlBennett\API\Models\Slack;

use \CarlBennett\API\Libraries\Model;

class Webhook extends Model {

  public $result;

  public function __construct() {
    parent::__construct();
    $this->result = null;
  }

}
