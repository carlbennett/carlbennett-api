<?php

namespace CarlBennett\API\Controllers\Software;

use \CarlBennett\API\Libraries\Controller;
use \CarlBennett\API\Libraries\Exceptions\UnspecifiedViewException;
use \CarlBennett\API\Libraries\Exceptions\MethodNotAllowedException;
use \CarlBennett\API\Libraries\Software as SoftwareLib;
use \CarlBennett\API\Libraries\Router;
use \CarlBennett\API\Models\Software\VerifyLicense as VerifyLicenseModel;
use \CarlBennett\API\Views\Software\VerifyLicenseJSON as VerifyLicenseJSONView;

class VerifyLicense extends Controller {

  public function run(Router &$router) {
    switch ($router->getRequestPathExtension()) {
      case "json": case "":
        $view = new VerifyLicenseJSONView();
      break;
      default:
        throw new UnspecifiedViewException();
    }
    $model = new VerifyLicenseModel();
    $this->verifyLicense($model, $router);
    ob_start();
    $view->render($model);
    $router->setResponseCode($model->http_code);
    $router->setResponseTTL(0);
    $router->setResponseHeader("Content-Type", $view->getMimeType());
    $router->setResponseContent(ob_get_contents());
    ob_end_clean();
  }

  protected function verifyLicense(
    VerifyLicenseModel &$model, Router &$router
  ) {
    $query_object  = (object) $router->getRequestQueryArray();
    $software      = new SoftwareLib();
    $model->result = $software->handleVerifyLicense(
      $query_object, $model->http_code
    );
  }

}
