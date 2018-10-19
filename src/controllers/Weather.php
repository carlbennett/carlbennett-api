<?php

namespace CarlBennett\API\Controllers;

use \CarlBennett\API\Libraries\WeatherReport;
use \CarlBennett\API\Models\Weather as WeatherModel;
use \CarlBennett\MVC\Libraries\Controller;
use \CarlBennett\MVC\Libraries\Router;
use \CarlBennett\MVC\Libraries\View;

class Weather extends Controller {
  public function &run( Router &$router, View &$view, array &$args ) {

    $model = new WeatherModel();
    $query = $router->getRequestQueryArray();

    $model->location = (
      isset($query[ 'location' ]) ? $query[ 'location' ] : ''
    );
    $model->weather_report = new WeatherReport( $model->location );

    $view->render( $model );

    $model->_responseCode = ( empty( $model->location ) ? 400 : 200 );
    $model->_responseHeaders[ 'Content-Type' ] = $view->getMimeType();
    $model->_responseTTL = 300;

    return $model;

  }
}
