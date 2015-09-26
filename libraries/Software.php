<?php

namespace CarlBennett\API\Libraries;

use \CarlBennett\API\Libraries\Common;
use \CarlBennett\API\Libraries\Logger;
use \StdClass;

class Software {

  const UPDATE_STATUS_BAD_REQUEST        = 0;
  const UPDATE_STATUS_PRODUCT_UNKNOWN    = 1;
  const UPDATE_STATUS_PRODUCT_DEPRECATED = 2;
  const UPDATE_STATUS_VERSION_UNKNOWN    = 3;
  const UPDATE_STATUS_VERSION_OLD        = 4;
  const UPDATE_STATUS_VERSION_CURRENT    = 5;

  public function &handleUpdate(StdClass &$data, &$http_code) {
    $http_code               = 400;
    $result                  = new StdClass();
    $result->status          = new StdClass();
    $result->status->code    = self::UPDATE_STATUS_BAD_REQUEST;
    $result->status->message = "Bad request";
    $result->update          = null;
    
    $result->product = (isset($data->product) ? $data->product : null);
    $result->version = (isset($data->version) ? $data->version : null);

    switch (strtolower($result->product)) {
      case null: case "": {
        break;
      }
      case "bnrbot": {
        // Until this is implemented, return unknown version.
        $http_code               = 200;
        $result->status->code    = self::UPDATE_STATUS_VERSION_UNKNOWN;
        $result->status->message = "Version unknown";
        break;
      }
      default: {
        $http_code               = 404;
        $result->status->code    = self::UPDATE_STATUS_PRODUCT_UNKNOWN;
        $result->status->message = "Unknown product";
      }
    }
    
    return $result;
  }

}
