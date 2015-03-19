<?php

namespace CarlBennett\API\Libraries;

use CarlBennett\API\Libraries\WeatherReport;

class Slack {

  public function handleWebhook(&$webhook_post_data) {
    $token        = $webhook_post_data->token;
    $team_id      = $webhook_post_data->team_id;
    $channel_id   = $webhook_post_data->channel_id;
    $channel_name = $webhook_post_data->channel_name;
    $user_id      = $webhook_post_data->user_id;
    $user_name    = $webhook_post_data->user_name;
    $command      = $webhook_post_data->command;
    $text         = $webhook_post_data->text;
    
    if (extension_loaded("newrelic")) {
      newrelic_add_custom_parameter("token", $token);
      newrelic_add_custom_parameter("team_id", $team_id);
      newrelic_add_custom_parameter("channel_id", $channel_id);
      newrelic_add_custom_parameter("channel_name", $channel_name);
      newrelic_add_custom_parameter("user_id", $user_id);
      newrelic_add_custom_parameter("user_name", $user_name);
      newrelic_add_custom_parameter("command", $command);
      newrelic_add_custom_parameter("text", $text);
    }

    $response = null;
    switch ($command) {
      case "/weather": {
        $location = trim($text);
        $info     = new WeatherReport($location);
        $response = $info;
        break;
      }
      default: {
        $response = "invalid_command: " . $command;
      }
    }
    return $response;
  }

}
