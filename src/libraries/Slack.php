<?php

namespace CarlBennett\API\Libraries;

use \CarlBennett\API\Libraries\Magic8Ball;
use \CarlBennett\MVC\Libraries\Common;
use \CarlBennett\MVC\Libraries\GeoIP;
use \CarlBennett\MVC\Libraries\Logger;

class Slack {

  public function handleWebhook(&$data) {
    foreach ($data as $k => $v) {
      Logger::logMetric($k, $v);
    }

    $token        = (isset($data["token"])
                  ? $data["token"] : null);
    $team_id      = (isset($data["team_id"])
                  ? $data["team_id"] : null);
    $team_domain  = (isset($data["team_domain"])
                  ? $data["team_domain"] : null);
    $channel_id   = (isset($data["channel_id"])
                  ? $data["channel_id"] : null);
    $channel_name = (isset($data["channel_name"])
                  ? $data["channel_name"] : null);
    $timestamp    = (isset($data["timestamp"])
                  ? $data["timestamp"] : null);
    $user_id      = (isset($data["user_id"])
                  ? $data["user_id"] : null);
    $user_name    = (isset($data["user_name"])
                  ? $data["user_name"] : null);
    $command      = (isset($data["command"])
                  ? $data["command"] : null);
    $text         = (isset($data["text"])
                  ? $data["text"] : null);
    $trigger_word = (isset($data["trigger_word"])
                  ? $data["trigger_word"] : null);

    if (empty($command) && !empty($trigger_word)) {
      $command = $trigger_word;
      $text    = preg_replace("#<https?://.*\|(.*)>#i", "$1", $text);
    }

    if (substr($text, 0, strlen($command)) === $command) {
      $text = substr($text, strlen($command) + 1);
    }

    $command = ltrim($command, "./");

    $response = null;
    switch ($command) {
      case "8ball":
      case "magic8ball": {
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
      case "dig":
      case "host":
      case "nslookup":
      case "whois": {
        $output = Common::shellSafeExecute($command, $text);
        if (empty($output)) {
          $response = "No output from the command-line program.";
        } else {
          $response = "```" . Common::stripExcessLines($output) . "```";
        }
        break;
      }
      case "geoip": {
        $ip = trim($text);
        if (empty($ip)) {
          $response = "Error: Please provide an IP address or hostname.";
        } else {
          $geoinfo = GeoIP::get($ip);
          $response = "query_address " . $ip . "\n";
          if ($geoinfo) {
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
      default: {
        $response = "invalid_command: " . $command;
      }
    }
    if (isset($trigger_word)) {
      return json_encode(["text" => $response]);
    } else {
      return $response;
    }
  }

}
