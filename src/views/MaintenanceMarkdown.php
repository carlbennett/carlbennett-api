<?php

namespace CarlBennett\API\Views;

use \CarlBennett\API\Models\Maintenance as MaintenanceModel;
use \CarlBennett\MVC\Libraries\Common;
use \CarlBennett\MVC\Libraries\Exceptions\IncorrectModelException;
use \CarlBennett\MVC\Libraries\Model;
use \CarlBennett\MVC\Libraries\View;

class MaintenanceMarkdown extends View {
  public function getMimeType() {
    return 'text/x-markdown;charset=utf-8';
  }

  public function render( Model &$model ) {
    if ( !$model instanceof MaintenanceModel ) {
      throw new IncorrectModelException();
    }
    echo "Carl Bennett's API is temporarily offline, check back later.\n";
  }
}
