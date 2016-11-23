<?php
/**
 *  carlbennett-api, a PHP-based API framework designed by @carlbennett
 *  Copyright (C) 2015-2016  Carl Bennett
 *  This file is part of carlbennett-api.
 *
 *  carlbennett-api is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  carlbennett-api is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with carlbennett-api.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace CarlBennett\API;

use \CarlBennett\API\Libraries\Exceptions\APIException;
use \CarlBennett\API\Libraries\Exceptions\ClassNotFoundException;
use \CarlBennett\API\Libraries\Cache;
use \CarlBennett\API\Libraries\Common;
use \CarlBennett\API\Libraries\Logger;
use \CarlBennett\API\Libraries\Router;
use \ReflectionClass;

function main() {

  spl_autoload_register(function($className){
    $path = $className;
    if (substr($path, 0, 15) == "CarlBennett\\API") $path = substr($path, 16);
    $cursor = strpos($path, "\\");
    if ($cursor !== false) {
      $path = strtolower(substr($path, 0, $cursor)) . substr($path, $cursor);
    }
    $path = str_replace("\\", "/", $path);
    $classShortName = $path;
    $path = "./" . $path . ".php";
    if (!file_exists($path)) {
      throw new ClassNotFoundException($classShortName);
    }
    require_once($path);
  });

  set_exception_handler(function($e){
    while (ob_get_level()) ob_end_clean();
    if ($e instanceof APIException) {
      http_response_code($e->getHTTPResponseCode());
    } else {
      http_response_code(500);
    }
    header("Cache-Control: max-age=0,must-revalidate,no-cache,no-store");
    header("Content-Type: application/json;charset=utf-8");
    header("Expires: 0");
    header("Pragma: max-age=0");
    if ($e instanceof APIException) {
      $additional_headers = $e->getHTTPResponseHeaders();
      foreach ($additional_headers as $key => $val) {
        header($key . ": " . $val);
      }
    }
    $flags = (Common::isBrowser(getenv("HTTP_USER_AGENT")) ? JSON_PRETTY_PRINT : 0);
    $json = [
      "error" => [
        "exception" => (new ReflectionClass($e))->getShortName(),
        "code" => $e->getCode(),
        "message" => $e->getMessage(),
      ],
    ];
    if (ini_get("display_errors")) {
      $json["error"]["file"] = Common::stripLeftPattern($e->getFile(), getenv("DOCUMENT_ROOT"));
      $json["error"]["line"] = $e->getLine();
    }
    Logger::logMetric("error_data", json_encode($json, JSON_PRETTY_PRINT));
    Logger::logException($e);
    die(json_encode($json, $flags));
  });

  set_error_handler(function($errno, $errstr, $errfile, $errline, $errcontext){
    if (!(error_reporting() & $errno)) return false;
    while (ob_get_level()) ob_end_clean();
    http_response_code(500);
    header("Cache-Control: max-age=0,must-revalidate,no-cache,no-store");
    header("Content-Type: application/json;charset=utf-8");
    header("Expires: 0");
    header("Pragma: max-age=0");
    $flags = (Common::isBrowser(getenv("HTTP_USER_AGENT")) ? JSON_PRETTY_PRINT : 0);
    $json = [
      "error" => [
        "exception" => null,
        "code" => Common::phpErrorName($errno),
      ],
    ];
    if (ini_get("display_errors")) {
      $json["error"]["message"] = $errstr;
      $json["error"]["file"] = Common::stripLeftPattern($errfile, getenv("DOCUMENT_ROOT"));
      $json["error"]["line"] = $errline;
    }
    Logger::logMetric("error_data", json_encode($json, JSON_PRETTY_PRINT));
    Logger::logError($errno, $errstr, $errfile, $errline, $errcontext);
    die(json_encode($json, $flags));
  });

  Logger::initialize();

  Common::$config  = json_decode(file_get_contents("../etc/config.json"));
  Common::$cache   = new Cache();
  Common::$version = Common::getVersionProperties();

  $router = new Router();
  $router->route();
  $router->send();

}

main();
