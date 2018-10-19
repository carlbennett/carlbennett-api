<?php

namespace CarlBennett\API\Controllers\HipChat;

use \CarlBennett\API\Libraries\Controller;
use \CarlBennett\API\Libraries\Exceptions\MethodNotAllowedException;
use \CarlBennett\API\Libraries\Exceptions\UnspecifiedViewException;
use \CarlBennett\API\Libraries\HipChat as HipChatLib;
use \CarlBennett\API\Libraries\Router;
use \CarlBennett\API\Models\HipChat\Webhook as HipChatWebhookModel;
use \CarlBennett\API\Views\HipChat\WebhookJSON as HipChatWebhookJSONView;

class Webhook extends Controller {

  public function run(Router &$router) {
    switch ($router->getRequestPathExtension()) {
      case "json": case "":
        $view = new HipChatWebhookJSONView();
      break;
      default:
        throw new UnspecifiedViewException();
    }
    if ($router->getRequestMethod() != "POST") {
      throw new MethodNotAllowedException(["POST"]);
    }
    $model = new HipChatWebhookModel();
    $model->result = (new HipChatLib())->handleWebhook(
      $router->getRequestBodyArray()
    );
    ob_start();
    $view->render($model);
    $router->setResponseCode(200);
    $router->setResponseTTL(0);
    $router->setResponseHeader("Content-Type", $view->getMimeType());
    $router->setResponseContent(ob_get_contents());
    ob_end_clean();
  }

}
