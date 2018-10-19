<?php

namespace CarlBennett\API\Libraries;

use \CarlBennett\API\Libraries\Exceptions\SoftwareLicenseNotFoundException
  as SLNFException;
use \CarlBennett\API\Libraries\Software\License as SoftwareLicense;
use \CarlBennett\MVC\Libraries\Common;
use \CarlBennett\MVC\Libraries\DatabaseDriver;
use \InvalidArgumentException;
use \StdClass;

class Software {

  const UPDATE_STATUS_BAD_REQUEST        = 0;
  const UPDATE_STATUS_PRODUCT_UNKNOWN    = 1;
  const UPDATE_STATUS_PRODUCT_DEPRECATED = 2;
  const UPDATE_STATUS_VERSION_UNKNOWN    = 3;
  const UPDATE_STATUS_VERSION_OLD        = 4;
  const UPDATE_STATUS_VERSION_CURRENT    = 5;

  const VERIFYLICENSE_STATUS_BAD_REQUEST = 0;
  const VERIFYLICENSE_STATUS_NOT_FOUND   = 1;
  const VERIFYLICENSE_STATUS_INACTIVE    = 2;
  const VERIFYLICENSE_STATUS_ACTIVE      = 3;

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

  public function &handleVerifyLicense(StdClass &$data, &$http_code) {
    $http_code = 400;

    $result                     = new StdClass();
    $result->issue_date         = null;
    $result->label              = null;
    $result->license            = null;
    $result->paypal_transaction = null;
    $result->status             = new StdClass();
    $result->status->code       = self::VERIFYLICENSE_STATUS_BAD_REQUEST;
    $result->status->message    = 'Bad request';

    $result->license = (isset($data->license) ? $data->license : null);

    if (is_null($result->license) || $result->license == "") {
      return $result;
    }

    try {
      $license = new SoftwareLicense($result->license);
    } catch (InvalidArgumentException $e) {
      // Malformed license string; bad request
      $result->status->message = 'Bad request: Malformed license string';
      return $result;
    } catch (SLNFException $e) {
      $http_code = 404;
      $result->status->code    = self::VERIFYLICENSE_STATUS_NOT_FOUND;
      $result->status->message = 'Unknown license';
      return $result;
    }

    $result->issue_date = $license->getIssueDate()->format( DATE_RFC2822 );
    $result->label = $license->getLabel();
    $result->paypal_transaction = $license->getPayPalTransaction();

    $http_code = 200;
    if (!$license->getActive()) {
      $result->status->code    = self::VERIFYLICENSE_STATUS_INACTIVE;
      $result->status->message = 'Inactive license';
    } else {
      $result->status->code    = self::VERIFYLICENSE_STATUS_ACTIVE;
      $result->status->message = 'Active license';
    }

    return $result;
  }

}
