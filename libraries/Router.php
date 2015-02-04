<?php

namespace CarlBennett\API\Libraries;

use CarlBennett\API\Controllers\HipChat as HipChatController;
use CarlBennett\API\Controllers\Status as StatusController;
use CarlBennett\API\Controllers\Weather as WeatherController;
use CarlBennett\API\Libraries\Common;
use CarlBennett\API\Libraries\Exceptions\ControllerNotFoundException;
use CarlBennett\API\Libraries\Exceptions\ServiceUnavailableException;

class Router {

  protected $hostname;
  protected $requestMethod;
  protected $requestURI;
  protected $pathArray;
  protected $pathString;
  protected $queryArray;
  protected $queryString;
  protected $requestHeaders;
  protected $requestBodyArray;
  protected $requestBodyString;
  protected $requestBodyMimeType;

  protected $responseCode;
  protected $responseHeaders;
  protected $responseContent;

  public function __construct() {
    $this->hostname = getenv("HTTP_HOST");
    if (empty($this->hostname)) $this->hostname = getenv("SERVER_NAME");
    $this->requestMethod = getenv("REQUEST_METHOD");
    $this->requestURI = getenv("REQUEST_URI");
    $cursor = strpos($this->requestURI, "?");
    if ($cursor !== false) {
      $this->pathString = substr($this->requestURI, 0, $cursor);
      $this->queryString = substr($this->requestURI, $cursor + 1);
    } else {
      $this->pathString = $this->requestURI;
      $this->queryString = "";
    }
    $this->pathArray = explode("/", $this->pathString);
    parse_str($this->queryString, $this->queryArray);
    $this->requestBodyMimeType = getenv("CONTENT_TYPE");
    $this->requestBodyString = $this->_getRequestBodyString();
    $this->requestBodyArray = $this->_getRequestBodyArray();
    $this->responseCode = 500;
    $this->responseHeaders = new \SplObjectStorage();
    $this->responseContent = "";
  }

  private function _getRequestBodyString() {
    $len = getenv("CONTENT_LENGTH");
    $buffer = "";
    if ($len === false) {
      $stdin = fopen("php://input", "rb");
      $buffer = stream_get_contents($stdin);
      fclose($stdin);
    } else {
      $len = (int)$len;
      $i = 0;
      $stdin = fopen("php://input", "r");
      while (!feof($stdin) && $i < $len) {
        $buffer .= fread($stdin, 8192); // 8291 is the default according to PHP documentation
      }
      fclose($stdin);
    }
    return $buffer;
  }

  private function _getRequestBodyArray() {
    if (stripos($this->requestBodyMimeType, "application/json") !== false || stripos($this->requestBodyMimeType, "text/json") !== false) {
      return json_decode($this->requestBodyString);
    } else if (stripos($this->requestBodyMimeType, "application/x-www-form-urlencoded") !== false) {
      $buffer;
      parse_str($this->requestBodyString, $buffer);
      return $buffer;
    } else {
      return null;
    }
  }

  public function addResponseContent($buffer) {
    $this->responseContent .= $buffer;
  }

  public function getHostname() {
    return $this->hostname;
  }

  public function getRequestMethod() {
    return $this->requestMethod;
  }

  public function getRequestPathArray() {
    return $this->pathArray;
  }

  public function getRequestPathExtension() {
    return pathinfo($this->pathString, PATHINFO_EXTENSION);
  }

  public function getRequestPathString() {
    return $this->pathString;
  }

  public function getRequestBodyArray() {
    return $this->requestBodyArray;
  }

  public function getRequestBodyString() {
    return $this->requestBodyString;
  }

  public function getRequestQueryArray() {
    return $this->queryArray;
  }

  public function getRequestQueryString() {
    return $this->queryString;
  }

  public function getRequestHeader($name) {
    foreach ($this->requestHeaders as $header) {
      if (strtolower($header->getName()) == strtolower($name)) return $header;
    }
    return false;
  }

  public function getRequestURI() {
    return $this->requestURI;
  }

  public function route() {
    $path = $this->getRequestPathArray()[1];
    if (extension_loaded("newrelic")) {
      newrelic_name_transaction("/" . $path);
    }
    if (Common::$settings->Router->maintenance) {
      throw new ServiceUnavailableException();
    }
    ob_start();
    switch ($path) {
      case "hipchat":
        $controller = new HipChatController();
      break;
      case "status":
      case "status.json":
      case "status.txt":
        $controller = new StatusController();
      break;
      case "weather":
      case "weather.json":
      case "weather.txt":
        $controller = new WeatherController();
      break;
      default:
        throw new ControllerNotFoundException($path);
    }
    if (extension_loaded("newrelic")) {
      newrelic_add_custom_parameter("controller", (new \ReflectionClass($controller))->getShortName());
    }
    $controller->run($this);
    $this->addResponseContent(ob_get_contents());
    ob_end_clean();
  }

  public function send() {
    http_response_code($this->responseCode);
    foreach ($this->responseHeaders as $header) {
      header($header->getName() . ": " . $header->getValue());
    }
    echo $this->responseContent;
  }

  public function setResponseCode($code) {
    $this->responseCode = $code;
  }

  public function setResponseContent($buffer) {
    $this->responseContent = $buffer;
  }

  public function setResponseHeader($arg1, $arg2 = null) {
    if ($arg1 instanceof HTTPHeader) {
      $this->responseHeaders->attach($arg1);
    } else if (is_string($arg1) && is_string($arg2)) {
      $this->responseHeaders->attach(new HTTPHeader($arg1, $arg2));
    } else {
      throw new \UnexpectedValueException("Arguments given must be two strings or an HTTPHeader object", -1);
    }
  }

}
