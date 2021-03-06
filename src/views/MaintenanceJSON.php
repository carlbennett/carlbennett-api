<?php

namespace CarlBennett\API\Views;

use \CarlBennett\API\Models\Maintenance as MaintenanceModel;
use \CarlBennett\MVC\Libraries\Common;
use \CarlBennett\MVC\Libraries\Exceptions\IncorrectModelException;
use \CarlBennett\MVC\Libraries\Model;
use \CarlBennett\MVC\Libraries\View;

class MaintenanceJSON extends View {
  public function getMimeType() {
    return 'application/json;charset=utf-8';
  }

  public function render( Model &$model ) {
    if ( !$model instanceof MaintenanceModel ) {
      throw new IncorrectModelException();
    }
    echo json_encode(
      "Carl Bennett's API is temporarily offline, check back later."
    , Common::prettyJSONIfBrowser() );
  }
}
