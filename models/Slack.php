<?php

namespace CarlBennett\API\Models;

use CarlBennett\API\Libraries\Common;
use CarlBennett\API\Libraries\Exceptions\MethodNotAllowedException;
use CarlBennett\API\Libraries\Slack as SlackLib;
use CarlBennett\API\Libraries\Model;
use CarlBennett\API\Libraries\Router;

class Slack extends Model {

  public $function;
  public $slack;
  public $result;

  public function __construct() {
    $this->function = null;
    $this->slack    = new SlackLib();
  }

  public function webhook(Router &$router) {
    $this->function = "webhook";
    if ($router->getRequestMethod() != "POST") {
      throw new MethodNotAllowedException(["POST"]);
      return;
    }
    $slack_webhook_data = (object)$router->getRequestBodyArray();
    $this->result = $this->slack->handleWebhook($slack_webhook_data);
  }

}
