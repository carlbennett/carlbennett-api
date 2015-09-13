<?php

namespace CarlBennett\API\Libraries;

use \CarlBennett\API\Libraries\Common;
use \CarlBennett\API\Libraries\Logger;
use \CarlBennett\API\Libraries\Magic8Ball;
use \CarlBennett\API\Libraries\WeatherReport;

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

    Logger::logMetric("token", $token);
    Logger::logMetric("team_id", $team_id);
    Logger::logMetric("channel_id", $channel_id);
    Logger::logMetric("channel_name", $channel_name);
    Logger::logMetric("user_id", $user_id);
    Logger::logMetric("user_name", $user_name);
    Logger::logMetric("command", $command);
    Logger::logMetric("text", $text);

    $response = null;
    switch ($command) {
      case "/8ball":
      case "/magic8ball": {
        $question = trim($text);
        if (strpos($question, "\n") !== false) {
          $response = "Only one-line sentences please!";
        } else if (substr(trim($question), -1) != "?") {
          $response = "Please ask me a question.";
        } else {
          $response = "> " . $question . "\n"
            . (new Magic8Ball())->getPrediction($question);
        }
        break;
      }
      case "/dig":
      case "/host":
      case "/nslookup": {
        $output = Common::shellSafeExecute(substr($command, 1), $text);
        if (empty($output)) {
          $response = "No output from the command-line program.";
        } else {
          $response = "```" . Common::stripExcessLines($output) . "```";
        }
        break;
      }
      case "/geoip": {
        $ip = trim($text);
        if (empty($ip)) {
          $response = "Error: Please provide an IP address or hostname.";
        } else {
          $geoinfo  = \geoip_record_by_name($ip);
          $response = "query_address " . $ip . "\n";
          if ($geoinfo) {
            ksort($geoinfo);
            foreach ($geoinfo as $key => $val) {
              if (!empty($val))
                $response .= "geoinfo_" . $key . " " . $val . "\n";
            }
          } else if (is_bool($geoinfo)) {
            $response .= "geoinfo " . ($geoinfo ? "true" : "false") . "\n";
          } else if (is_null($geoinfo)) {
            $response .= "geoinfo null\n";
          } else {
            $response .= "geoinfo " . gettype($geoinfo) . "\n";
          }
          $response = "```" . $response . "```";
        }
        break;
      }
      case "/weather": {
        $location = trim($text);
        $info     = (new WeatherReport($location))->getAsMarkdown();
        if ($info === false) {
          $response = "*Error:* unable to download report"
           . " or location not given.";
        } else {
          $response = str_replace("**", "*", $info);
        }
        break;
      }
      default: {
        $response = "invalid_command: " . $command;
      }
    }
    return $response;
  }

}
