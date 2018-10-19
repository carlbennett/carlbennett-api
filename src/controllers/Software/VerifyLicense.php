<?php

namespace CarlBennett\API\Controllers\Software;

use \CarlBennett\API\Libraries\Software as SoftwareLib;
use \CarlBennett\API\Models\Software\VerifyLicense as VerifyLicenseModel;
use \CarlBennett\MVC\Libraries\Controller;
use \CarlBennett\MVC\Libraries\Router;
use \CarlBennett\MVC\Libraries\View;

class VerifyLicense extends Controller {
  public function &run( Router &$router, View &$view, array &$args ) {

    $model = new VerifyLicenseModel();
    $query_object = (object) $router->getRequestQueryArray();

    $model->result = ( new SoftwareLib() )->handleVerifyLicense(
      $query_object, $model->http_code
    );

    $view->render($model);

    $model->_responseCode = $model->http_code;
    $model->_responseHeaders[ 'Content-Type' ] = $view->getMimeType();
    $model->_responseTTL = 300;

    return $model;

  }
}
