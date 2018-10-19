<?php

namespace CarlBennett\API\Views;

use \CarlBennett\API\Libraries\Exceptions\IncorrectModelException;
use \CarlBennett\API\Libraries\Model;
use \CarlBennett\API\Libraries\View;
use \CarlBennett\API\Models\Status as StatusModel;
use \CarlBennett\MVC\Libraries\ArrayFlattener;

class StatusPlain extends View {

  public function getMimeType() {
    return "text/plain;charset=utf-8";
  }

  public function render(Model &$model) {
    if (!$model instanceof StatusModel) {
      throw new IncorrectModelException();
    }
    echo ArrayFlattener::flatten( $model->status );
  }

}
