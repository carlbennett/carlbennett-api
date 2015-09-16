<?php

namespace CarlBennett\API\Views;

use \CarlBennett\API\Libraries\Common;
use \CarlBennett\API\Libraries\Exceptions\IncorrectModelException;
use \CarlBennett\API\Libraries\Model;
use \CarlBennett\API\Libraries\View;
use \CarlBennett\API\Models\Status as StatusModel;

class StatusPlain extends View {

  public function getMimeType() {
    return "text/plain;charset=utf-8";
  }

  public function render(Model &$model) {
    if (!$model instanceof StatusModel) {
      throw new IncorrectModelException();
    }
    echo "remote_address " . $model->remote_address . "\n";
    if ($model->remote_geoinfo) {
      foreach ($model->remote_geoinfo as $key => $val) {
        if (!empty($val))
          echo "remote_geoinfo_" . $key . " " . $val . "\n";
      }
    } else if (is_bool($model->remote_geoinfo)) {
      echo "remote_geoinfo "
        . ($model->remote_geoinfo ? "true" : "false") . "\n";
    } else if (is_null($model->remote_geoinfo)) {
      echo "remote_geoinfo null\n";
    } else {
      echo "remote_geoinfo " . gettype($model->remote_geoinfo) . "\n";
    }
    echo "timestamp " . $model->timestamp->format("r") . "\n";
    foreach ($model->version_info as $key => $val) {
      echo "version_info_" . $key . " " . $val . "\n";
    }
  }

}
