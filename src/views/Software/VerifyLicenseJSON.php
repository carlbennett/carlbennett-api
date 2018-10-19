<?php

namespace CarlBennett\API\Views\Software;

use \CarlBennett\API\Models\Software\VerifyLicense as VerifyLicenseModel;
use \CarlBennett\MVC\Libraries\Common;
use \CarlBennett\MVC\Libraries\Exceptions\IncorrectModelException;
use \CarlBennett\MVC\Libraries\Model;
use \CarlBennett\MVC\Libraries\View;

class VerifyLicenseJSON extends View {
  public function getMimeType() {
    return 'application/json;charset=utf-8';
  }

  public function render( Model &$model ) {
    if ( !$model instanceof VerifyLicenseModel ) {
      throw new IncorrectModelException();
    }
    echo json_encode(
      [ 'result' => $model->result ], Common::prettyJSONIfBrowser()
    );
  }
}
