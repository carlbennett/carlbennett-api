<?php

namespace CarlBennett\API\Models;

use CarlBennett\API\Libraries\Common;
use CarlBennett\API\Libraries\Exceptions\OAuthException;
use CarlBennett\API\Libraries\Model;
use CarlBennett\API\Libraries\Router;

class OAuth extends Model {

  public $access_token;
  public $code;
  public $function;
  public $user;
  public $ig;

  public function __construct() {
    $this->access_token = null;
    $this->code         = null;
    $this->function     = null;
    $this->user         = null;
  }

  public function callback(Router &$router) {
    $this->function = "callback";
    $params = $router->getRequestQueryArray();
    $code = (isset($params["code"]) ? $params["code"] : "");
    $response = Common::curlRequest("https://api.instagram.com/oauth/access_token", [
      "client_id"     => "65f5b5b1a9f648d4a899bd28e96fcbda",
      "client_secret" => "8a39e81ba22a477ab51c85567d0bd712",
      "grant_type"    => "authorization_code",
      "redirect_uri"  => "https://api.carlbennett.me/oauth/callback?vendor=oauth_ig",
      "code"          => $code,
    ]);
    $json = json_decode($response->data);
    if (isset($json->code) && isset($json->error_type) && isset($json->error_message)) {
      throw new OAuthException("Vendor response error");
    } else if (isset($json->access_token) && isset($json->user)) {
      $this->access_token = $json->access_token;
      $this->user         = $json->user;
    } else {
      throw new OAuthException("Failed to parse vendor response");
    }
  }

}
