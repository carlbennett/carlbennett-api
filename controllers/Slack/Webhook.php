<?php

namespace CarlBennett\API\Controllers\Slack;

use \CarlBennett\API\Libraries\Controller;
use \CarlBennett\API\Libraries\Exceptions\UnspecifiedViewException;
use \CarlBennett\API\Libraries\Exceptions\MethodNotAllowedException;
use \CarlBennett\API\Libraries\Slack as SlackLib;
use \CarlBennett\API\Libraries\Router;
use \CarlBennett\API\Models\Slack\Webhook as SlackWebhookModel;
use \CarlBennett\API\Views\Slack\WebhookMarkdown as SlackWebhookMarkdownView;

class Webhook extends Controller {

  public function run(Router &$router) {
    switch ($router->getRequestPathExtension()) {
      case "":
      case "md":
        $view = new SlackWebhookMarkdownView();
      break;
      default:
        throw new UnspecifiedViewException();
    }
    if ($router->getRequestMethod() != "POST") {
      throw new MethodNotAllowedException(["POST"]);
    }
    $model         = new SlackWebhookModel();
    $webhook_data  = (object)$router->getRequestBodyArray();
    $model->result = (new SlackLib())->handleWebhook($webhook_data);
    ob_start();
    $view->render($model);
    $router->setResponseCode(200);
    $router->setResponseTTL(0);
    $router->setResponseHeader("Content-Type", $view->getMimeType());
    $router->setResponseContent(ob_get_contents());
    ob_end_clean();
  }

}
