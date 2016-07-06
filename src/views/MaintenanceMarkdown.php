<?php

namespace CarlBennett\API\Views;

use \CarlBennett\API\Libraries\Common;
use \CarlBennett\API\Libraries\Exceptions\IncorrectModelException;
use \CarlBennett\API\Libraries\Model;
use \CarlBennett\API\Libraries\View;
use \CarlBennett\API\Models\Maintenance as MaintenanceModel;

class MaintenanceMarkdown extends View {

  public function getMimeType() {
    return "text/x-markdown;charset=utf-8";
  }

  public function render(Model &$model) {
    if (!$model instanceof MaintenanceModel) {
      throw new IncorrectModelException();
    }
    echo "Carl Bennett's API is temporarily offline, check back later.\n";
  }

}
