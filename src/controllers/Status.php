<?php

namespace CarlBennett\API\Controllers;

use \CarlBennett\API\Libraries\VersionInfo;
use \CarlBennett\API\Models\Status as StatusModel;
use \CarlBennett\MVC\Libraries\Common;
use \CarlBennett\MVC\Libraries\Controller;
use \CarlBennett\MVC\Libraries\GeoIP;
use \CarlBennett\MVC\Libraries\Router;
use \CarlBennett\MVC\Libraries\View;
use \DateTime;
use \DateTimeZone;
use \StdClass;

class Status extends Controller {
  public function &run( Router &$router, View &$view, array &$args ) {
    $model = new StatusModel();

    $this->getStatus( $model );

    $view->render( $model );

    $model->_responseCode = 200;
    $model->_responseHeaders[ 'Content-Type' ] = $view->getMimeType();
    $model->_responseTTL = 300;

    return $model;

  }

  protected function getStatus( StatusModel &$model ) {
    $status = new StdClass();

    $utc = new DateTimeZone( 'Etc/UTC' );

    $status->healthcheck       = true;
    $status->is_browser        = Common::isBrowser(getenv( 'HTTP_USER_AGENT' ));
    $status->remote_address    = getenv( 'REMOTE_ADDR' );
    $status->remote_geoinfo    = GeoIP::get( $status->remote_address );
    $status->remote_user_agent = getenv( 'HTTP_USER_AGENT' );
    $status->timestamp         = new DateTime( 'now', $utc );
    $status->version_info      = VersionInfo::$version;

    $model->status = $status;

    return true;
  }
}
