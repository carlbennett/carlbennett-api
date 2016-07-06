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

    if (is_null($result->product) || $result->product == "") {
      return $result;
    }

    $product_found = false;
    foreach (Common::$config->Software->products as $product) {
      if (strtolower($product->name) == strtolower($result->product)) {
        $product_found = true;
        $new_version   = null;
        $http_code     = 200;
        foreach ($product->versions as $version) {
          if (version_compare(
            $result->version, $version->version, "<"
          )) {
            $new_version = $version;
          }
        }
        if (!is_null($new_version)) {
          $result->status->code    = self::UPDATE_STATUS_VERSION_OLD;
          $result->status->message = "Version is old";
          $result->update          = new StdClass();
          $result->update->url     = $version->url;
          $result->update->version = $version->version;
        } else {
          $result->status->code    = self::UPDATE_STATUS_VERSION_CURRENT;
          $result->status->message = "Version is current";
        }
        break;
      }
    }
    if (!$product_found) {
      $http_code               = 404;
      $result->status->code    = self::UPDATE_STATUS_PRODUCT_UNKNOWN;
      $result->status->message = "Unknown product";
    }

    return $result;
  }

}
