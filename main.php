<?php

use CarlBennett\API\Libraries\Exceptions\APIException;
use CarlBennett\API\Libraries\Exceptions\ExtensionMissingException;
use CarlBennett\API\Libraries\Common;
use CarlBennett\API\Libraries\Router;

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
      throw new ExtensionMissingException($classShortName);
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
    $flags = (Common::isBrowser(getenv("HTTP_USER_AGENT")) ? JSON_PRETTY_PRINT : 0);
    $json = [
      "error" => [
        "exception" => (new ReflectionClass($e))->getShortName(),
        "code" => $e->getCode(),
        "message" => $e->getMessage(),
      ],
    ];
    if (ini_get("display_errors")) {
      $json["error"]["file"] = Common::stripLeftPattern($e->getFile(), "/home/nginx/carlbennett-api");
      $json["error"]["line"] = $e->getLine();
    }
    if (extension_loaded("newrelic")) {
      newrelic_add_custom_parameter("error_data", json_encode($json, JSON_PRETTY_PRINT));
      newrelic_notice_error($e->getMessage(), $e);
    }
    die(json_encode($json, $flags));
  });

  set_error_handler(function($errno, $errstr, $errfile, $errline, $errcontext){
    while (ob_get_level()) ob_end_clean();
    if (!(error_reporting() & $errno)) return false;
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
      $json["error"]["file"] = Common::stripLeftPattern($errfile, "/home/nginx/carlbennett-api");
      $json["error"]["line"] = $errline;
    }
    if (extension_loaded("newrelic")) {
      newrelic_add_custom_parameter("error_data", json_encode($json, JSON_PRETTY_PRINT));
      newrelic_notice_error($errno, $errstr, $errfile, $errline, $errcontext);
    }
    die(json_encode($json, $flags));
  });

  array_map(function($extensionName){
    if (!extension_loaded($extensionName)) {
      throw new ExtensionMissingException($extensionName);
    }
  }, ["http", "json"]);

  if (extension_loaded("newrelic")) {
    newrelic_disable_autorum();
    newrelic_name_transaction("null");
    newrelic_add_custom_parameter("REMOTE_ADDR", getenv("REMOTE_ADDR"));
  }

  Common::$settings = json_decode(file_get_contents("./settings.json"));

  $router = new Router();
  $router->route();
  $router->send();

}

main();
