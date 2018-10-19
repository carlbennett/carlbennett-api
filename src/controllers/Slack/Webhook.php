<?php

namespace CarlBennett\API\Controllers\Slack;

use \CarlBennett\API\Libraries\Slack as SlackLib;
use \CarlBennett\API\Models\Slack\Webhook as SlackWebhookModel;
use \CarlBennett\MVC\Libraries\Controller;
use \CarlBennett\MVC\Libraries\Router;
use \CarlBennett\MVC\Libraries\View;

class Webhook extends Controller {
  public function &run( Router &$router, View &$view, array &$args ) {

    $model = new SlackWebhookModel();

    if ( $router->getRequestMethod() != 'POST' ) {
      $model->_responseCode = 405;
      $model->_responseHeaders[ 'Allow' ] = 'POST';
      $model->_responseTTL = 0;
      return $model;
    }

    $body = $router->getRequestBodyArray();

    $model->result = ( new SlackLib() )->handleWebhook( $body );

    $view->render( $model );

    $model->_responseCode = 200;
    $model->_responseHeaders[ 'Content-Type' ] = $view->getMimeType();
    $model->_responseTTL = 0;

    return $model;

  }
}
