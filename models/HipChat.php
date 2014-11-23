<?php

namespace CarlBennett\API\Models;

use CarlBennett\API\Libraries\Common;
use CarlBennett\API\Libraries\HipChat as HipChatLib;
use CarlBennett\API\Libraries\Model;
use CarlBennett\API\Libraries\Router;

class HipChat extends Model {

  public $function;
  public $hipchat;
  public $result;

  public function __construct() {
    $this->function = null;
    $this->hipchat  = new HipChatLib();
  }

  public function webhook(Router &$router) {
    $this->function       = "webhook";
    $hipchat_webhook_data = $router->getRequestBodyArray();
    $this->result         = $this->hipchat->handleWebhook($hipchat_webhook_data);
  }

}
