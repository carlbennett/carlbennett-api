<?php

namespace CarlBennett\API\Controllers;

use \CarlBennett\API\Libraries\Controller;
use \CarlBennett\API\Libraries\Exceptions\UnspecifiedViewException;
use \CarlBennett\API\Libraries\Router;
use \CarlBennett\API\Libraries\VersionInfo;
use \CarlBennett\API\Models\Status as StatusModel;
use \CarlBennett\API\Views\StatusJSON as StatusJSONView;
use \CarlBennett\API\Views\StatusPlain as StatusPlainView;
use \CarlBennett\MVC\Libraries\GeoIP;
use \DateTime;
use \DateTimeZone;
use \StdClass;

class Status extends Controller {

  public function run(Router &$router) {
    switch ($router->getRequestPathExtension()) {
      case "json": case "":
        $view = new StatusJSONView();
      break;
      case "txt":
        $view = new StatusPlainView();
      break;
      default:
        throw new UnspecifiedViewException();
    }
    $model = new StatusModel();
    $this->getStatus($model);
    ob_start();
    $view->render($model);
    $router->setResponseCode(200);
    $router->setResponseTTL(300);
    $router->setResponseHeader("Content-Type", $view->getMimeType());
    $router->setResponseContent(ob_get_contents());
    ob_end_clean();
  }

  protected function getStatus(StatusModel &$model) {
    $status = new StdClass();

    $utc = new DateTimeZone( 'Etc/UTC' );

    $status->healthcheck       = true;
    $status->remote_address    = getenv( 'REMOTE_ADDR' );
    $status->remote_geoinfo    = GeoIP::get( $status->remote_address );
    $status->remote_user_agent = getenv( 'HTTP_USER_AGENT' );
    $status->timestamp         = new DateTime( 'now', $utc );
    $status->version_info      = VersionInfo::$version;

    $model->status = $status;

    return true;
  }

}
