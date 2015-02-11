<?php

namespace CarlBennett\API\Libraries;

use CarlBennett\API\Libraries\Common;

class WeatherReport {

  protected $location;

  public function __construct($location) {
    $this->location = $location;
  }

  public static function directionString($degree, $html) {
    $deg = ($html ? "&deg;" : mb_convert_encoding(chr(176), "UTF-8", "ASCII"));
    $degree %= 360;
    if ($degree >= 0   && $degree < 45 ) return "N";
    if ($degree >= 45  && $degree < 90 ) return "NE";
    if ($degree >= 90  && $degree < 135) return "E";
    if ($degree >= 135 && $degree < 180) return "SE";
    if ($degree >= 180 && $degree < 225) return "S";
    if ($degree >= 225 && $degree < 270) return "SW";
    if ($degree >= 270 && $degree < 315) return "W";
    if ($degree >= 315 && $degree < 360) return "NW";
    return "Unknown (" . (int)$degree . $deg . ")";
  }

  public function downloadReport() {
    $query = "select * from weather.forecast "
      . "where woeid in (select woeid from geo.places(1) where text=\""
      . filter_var($this->location, FILTER_UNSAFE_RAW)
      . "\")";
    $url = "https://query.yahooapis.com/v1/public/yql?q="
      . urlencode($query)
      . "&format=json&env=store://datatables.org/alltableswithkeys";
    $cache_key = "weather-" . md5($query);
    $cache_val = Common::$cache->get($cache_key);
    if ($cache_val !== false) {
      return json_decode(unserialize($cache_val)->data);
    }
    $response = Common::curlRequest($url, null, "");
    if (!$response || $response->code != 200 || empty($response->data)) {
      // Not a valid response
      return false;
    }
    if (stripos($response->type, "application/json") === false &&
        stripos($response->type, "text/json") === false) {
      // Not expected JSON mime-type
      return $response->type;
    }
    Common::$cache->set($cache_key, serialize($response), 300);
    return json_decode($response->data);
  }

  public function standardizeReport(&$raw_report) {
    if ($raw_report->query->count < 1 ||
        is_null($raw_report->query->results)) {
      return false;
    }
    $subreport = $raw_report->query->results->channel;

    $report                   = new \StdClass();
    $report->city             = $subreport->location->city;
    $report->region           = $subreport->location->region;
    $report->country          = $subreport->location->country;
    $report->unit_temperature = $subreport->units->temperature;
    $report->unit_speed       = $subreport->units->speed;
    $report->condition        = [$subreport->item->condition->temp,
                                 $subreport->item->condition->text];
    $report->wind_chill       = $subreport->wind->chill;
    $report->wind_speed       = $subreport->wind->speed;
    $report->wind_direction   = $subreport->wind->direction;
    $report->humidity         = $subreport->atmosphere->humidity;
    $report->sunrise          = $subreport->astronomy->sunrise;
    $report->sunset           = $subreport->astronomy->sunset;

    $report->location         = "";
    if (!empty($report->city)) {
      if (!empty($report->location)) $report->location .= ", ";
      $report->location .= $report->city;
    }
    if (!empty($report->region) && $report->country != $report->region) {
      if (!empty($report->location)) $report->location .= ", ";
      $report->location .= $report->region;
    }
    if (!empty($report->country) && $report->city != $report->country) {
      if (!empty($report->location)) $report->location .= ", ";
      $report->location .= $report->country;
    }

    // clean up the object a bit
    $report->condition[0]   = (int)str_replace(",", "", $report->condition[0]);
    $report->wind_chill     = (int)$report->wind_chill;
    $report->wind_speed     = (int)$report->wind_speed;
    $report->wind_direction = (int)$report->wind_direction;
    $report->humidity       = (int)$report->humidity;

    return $report;
  }

  public function standardizeReportHell() {
    $report                   = new \StdClass();

    mt_srand(time() / 3600); // change the mt_rand() seed every hour.

    $report->city             = null;
    $report->country          = null;
    $report->region           = "Hell";
    $report->unit_temperature = "F";
    $report->unit_speed       = "mph";
    $report->condition        = [10000000000, "Blistering Hot"];
    $report->wind_chill       = $report->condition[0];
    $report->wind_speed       = mt_rand(0, 5);
    $report->wind_direction   = mt_rand(0, 359);
    $report->humidity         = mt_rand(90, 100);
    $report->sunrise          = null;
    $report->sunset           = null;

    $report->location         = "Hell";

    return $report;
  }

  public function getAsHtml() {
    $report = $this->getAsObject();
    if (!$report) {
      return false;
    }
    $message = ""
      . "<b>Weather Report:</b> " . $report->location . "<br/>"
      . "<b>Condition:</b> "
        . $report->condition[1] . ", "
        . number_format($report->condition[0]) . "&deg;"
        . $report->unit_temperature . " "
      . "<b>Wind Chill:</b> "
        . number_format($report->wind_chill) . "&deg;"
        . $report->unit_temperature . "<br/>"
      . "<b>Humidity:</b> "
        . $report->humidity . "% "
      . "<b>Wind:</b> "
        . ($report->wind_speed != 0 ? ""
        . self::directionString($report->wind_direction, true)
        . " at " : "")
        . $report->wind_speed
        . " "
        . $report->unit_speed . "<br/>"
      . (!empty($report->sunrise) &&
         !empty($report->sunset) ? ""
      . "<b>Sunrise:</b> "
        . $report->sunrise . " "
      . "<b>Sunset:</b> "
        . $report->sunset . "<br/>" : "")
    ;
    return $message;
  }

  public function getAsMessage(&$webhook_post_data) {
    $message          = new \StdClass();
    $message->message = $this->getAsHtml();
    $message->format  = "html";
    if ($message->message === false) {
      $message->color = "red";
      $their_name = $webhook_post_data->item->message->from->name;
      switch (mt_rand(0, 3)) {
        case 0: $message->message = "Hey now, where do you live, "
          . $their_name . "? Is it even in the Milky Way?"; break;
        case 1: $message->message = "I don't even."; break;
        case 2: $message->message = "What do I look like? "
          . "A mind reader?"; break;
        default: $message->message = "<b>Error:</b> "
          . "Cannot retrieve weather information.";
      }
    } else {
      $message->color = "gray";
    }
    return $message;
  }

  public function getAsObject() {
    if ($this->location == 666 || strtolower($this->location) == "hell") {
      $report = $this->standardizeReportHell();
    } else {
      $report = $this->downloadReport();
      $report = $this->standardizeReport($report);
    }
    return $report;
  }

  public function getAsPlain() {
    $report = $this->getAsObject();
    if (!$report) {
      return false;
    }
    $deg = mb_convert_encoding(chr(176), "UTF-8", "ASCII");
    $message = ""
      . "Weather Report: " . $report->location . "\n"
      . "Condition: "
        . $report->condition[1] . ", "
        . number_format($report->condition[0]) . $deg
        . $report->unit_temperature . " "
      . "Wind Chill: "
        . number_format($report->wind_chill) . $deg
        . $report->unit_temperature . "\n"
      . "Humidity: "
        . $report->humidity . "% "
      . "Wind: "
        . ($report->wind_speed != 0 ? ""
        . self::directionString($report->wind_direction, false)
        . " at " : "")
        . $report->wind_speed
        . " "
        . $report->unit_speed . "\n"
      . (!empty($report->sunrise) &&
         !empty($report->sunset) ? ""
      . "Sunrise: "
        . $report->sunrise . " "
      . "Sunset: "
        . $report->sunset . "\n" : "")
    ;
    return $message;
  }

}
