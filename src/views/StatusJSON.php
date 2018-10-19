<?php

namespace CarlBennett\API\Views;

use \CarlBennett\API\Libraries\Exceptions\IncorrectModelException;
use \CarlBennett\API\Libraries\Model;
use \CarlBennett\API\Libraries\View;
use \CarlBennett\API\Models\Status as StatusModel;
use \CarlBennett\MVC\Libraries\Common;

class StatusJSON extends View {

  public function getMimeType() {
    return "application/json;charset=utf-8";
  }

  public function render(Model &$model) {
    if (!$model instanceof StatusModel) {
      throw new IncorrectModelException();
    }
    echo json_encode( $model->status, Common::prettyJSONIfBrowser() );
  }

}
