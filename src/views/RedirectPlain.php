<?php

namespace CarlBennett\API\Views;

use \CarlBennett\API\Libraries\Common;
use \CarlBennett\API\Libraries\Exceptions\IncorrectModelException;
use \CarlBennett\API\Libraries\Model;
use \CarlBennett\API\Libraries\View;
use \CarlBennett\API\Models\Redirect as RedirectModel;

class RedirectPlain extends View {

  public function getMimeType() {
    return "text/plain;charset=utf-8";
  }

  public function render(Model &$model) {
    if (!$model instanceof RedirectModel) {
      throw new IncorrectModelException();
    }
    echo "Redirect (" . $model->redirect_code . "): "
      . $model->redirect_to . "\n";
  }

}
