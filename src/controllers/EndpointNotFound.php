<?php

namespace CarlBennett\API\Controllers;

use \CarlBennett\API\Models\EndpointNotFound as EndpointNotFoundModel;
use \CarlBennett\MVC\Libraries\Controller;
use \CarlBennett\MVC\Libraries\Router;
use \CarlBennett\MVC\Libraries\View;

class EndpointNotFound extends Controller {
  public function &run( Router &$router, View &$view, array &$args ) {

    $model = new EndpointNotFoundModel();

    $view->render( $model );

    $model->_responseCode = 404;
    $model->_responseHeaders[ 'Content-Type' ] = $view->getMimeType();
    $model->_responseTTL = 0;

    return $model;

  }
}
