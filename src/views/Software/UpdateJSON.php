<?php

namespace CarlBennett\API\Views\Software;

use \CarlBennett\API\Libraries\Common;
use \CarlBennett\API\Libraries\Exceptions\IncorrectModelException;
use \CarlBennett\API\Libraries\Model;
use \CarlBennett\API\Libraries\View;
use \CarlBennett\API\Models\Software\Update as SoftwareUpdateModel;

class UpdateJSON extends View {

  public function getMimeType() {
    return "application/json;charset=utf-8";
  }

  public function render(Model &$model) {
    if (!$model instanceof SoftwareUpdateModel) {
      throw new IncorrectModelException();
    }
    $flags = (Common::isBrowser(getenv("HTTP_USER_AGENT")) ? JSON_PRETTY_PRINT : 0);
    echo json_encode(["result" => $model->result], $flags) . "\n";
  }

}
