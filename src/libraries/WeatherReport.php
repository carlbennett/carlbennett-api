<?php

namespace CarlBennett\API\Libraries;

use \CarlBennett\API\Libraries\Common;
use \StdClass;

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

  public function calculateHeatIndex($temperature, $humidity) {
    // <https://en.wikipedia.org/wiki/Heat_index#Formula>
    $C1 = -42.379;
    $C2 = 2.04901523;
    $C3 = 10.14333127;
    $C4 = -0.22475541;
    $C5 = -0.00683783;
    $C6 = -0.05481717;
    $C7 = 0.00122874;
    $C8 = 0.00085282;
    $C9 = -0.00000199;
    $T  = ((float)$temperature);
    $R  = ((float)$humidity);
    $HI = $C1
        + $C2 * $T
        + $C3 * $R
        + $C4 * $T * $R
        + $C5 * $T * $T
        + $C6 * $R * $R
        + $C7 * $T * $T * $R
        + $C8 * $T * $R * $R
        + $C9 * $T * $T * $R * $R
    ;
    return ($T < 80 || $R < 40 ? null : $HI);
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
    Common::$cache->set($cache_key, serialize($response), 600);
    return json_decode($response->data);
  }

  private static function nullIfUnset( $object, $key ) {
    return (isset($object->$key) ? $object->$key : null);
  }

  public static function padTime($str_time) {
    // Converts "7:1 am" to "7:01 am" as an example
    $a = explode(" ", $str_time);
    $t = explode(":", array_shift($a));
    $h = (isset($t[0]) ? (int) $t[0] : null);
    $m = (isset($t[1]) ? (int) $t[1] : null);
    $s = (isset($t[2]) ? (int) $t[2] : null);

    if ($m) $m = substr("0" . $m, -2);
    if ($s) $s = substr("0" . $s, -2);

    if (!$h || !$m) return null;
    $r = $h . ":" . $m;
    if ($s) $r .= ":" . $s;
    $r .= " " . implode(" ", $a);
    return $r;
  }

  public function standardizeReport(&$raw_report) {
    if ($raw_report->query->count < 1 ||
        is_null($raw_report->query->results)) {
      return false;
    }
    $subreport = $raw_report->query->results->channel;

    $location = self::nullIfUnset($subreport, "location");
    $item = self::nullIfUnset($subreport, "item");
    $wind = self::nullIfUnset($subreport, "wind");
    $atmos = self::nullIfUnset($subreport, "atmosphere");
    $astro = self::nullIfUnset($subreport, "astronomy");

    if (!$location) {
      return false;
    }

    // create our object based on their object
    $report                   = new StdClass();
    $report->location         = ($location ? null : $location);
    $report->city             = self::nullIfUnset($location, "city");
    $report->region           = self::nullIfUnset($location, "region");
    $report->country          = self::nullIfUnset($location, "country");
    $report->unit_temperature = $subreport->units->temperature;
    $report->unit_speed       = $subreport->units->speed;
    $report->condition        = (
      $item ? [$item->condition->temp, $item->condition->text] : [null, null]
    );
    $report->wind_chill       = self::nullIfUnset($wind, "chill");
    $report->wind_speed       = self::nullIfUnset($wind, "speed");
    $report->wind_direction   = self::nullIfUnset($wind, "direction");
    $report->humidity         = self::nullIfUnset($atmos, "humidity");
    $report->heat_index       = null; // We calculate this at the end.
    $report->sunrise          = self::nullIfUnset($astro, "sunrise");
    $report->sunset           = self::nullIfUnset($astro, "sunset");

    // ensure our object types are how we intend
    $report->city             = (string) trim($report->city);
    $report->region           = (string) trim($report->region);
    $report->country          = (string) trim($report->country);
    $report->unit_temperature = (string) trim($report->unit_temperature);
    $report->unit_speed       = (string) trim($report->unit_speed);
    $report->condition[0]     = (int) str_replace(
                                  ",", "", $report->condition[0]
                                );
    $report->condition[1]     = (string) trim($report->condition[1]);
    $report->wind_chill       = (
                                  $report->condition[0] > 50 ? null :
                                  (int) $report->wind_chill
                                );
    $report->wind_speed       = (int) $report->wind_speed;
    $report->wind_direction   = (int) $report->wind_direction;
    $report->humidity         = (int) $report->humidity;
    $report->sunrise          = (string) trim($report->sunrise);
    $report->sunset           = (string) trim($report->sunset);

    // calculate the heat index (not provided in their report)
    $report->heat_index     = $this->calculateHeatIndex(
                                $report->condition[0],
                                $report->humidity
                              );

    // their report uses ints and concatenates them improperly, let's fix that
    $report->sunrise = self::padTime($report->sunrise);
    $report->sunset  = self::padTime($report->sunset);

    // create the location based on their report
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

    return $report;
  }

  public function standardizeReportHell() {
    $report                   = new StdClass();

    mt_srand(time() / 3600); // change the mt_rand() seed every hour.

    $report->city             = null;
    $report->country          = null;
    $report->region           = "Hell";
    $report->unit_temperature = "F";
    $report->unit_speed       = "mph";
    $report->condition        = [mt_rand(1000000, 10000000), "Blistering Hot"];
    $report->wind_chill       = null;
    $report->wind_speed       = mt_rand(0, 5);
    $report->wind_direction   = mt_rand(0, 359);
    $report->humidity         = mt_rand(0, 100);
    $report->heat_index       = $this->calculateHeatIndex(
                                  $report->condition[0],
                                  $report->humidity
                                );
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
      . (!is_null($report->wind_chill) ?
        "<b>Wind Chill:</b> "
        . number_format($report->wind_chill) . "&deg;"
        . $report->unit_temperature : "")
      . (!is_null($report->heat_index) ?
        "<b>Heat Index:</b> "
        . number_format($report->heat_index) . "&deg;"
        . $report->unit_temperature : "")
      . "<br/>"
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

  public function getAsMarkdown() {
    $report = $this->getAsObject();
    if (!$report) {
      return false;
    }
    $deg = mb_convert_encoding(chr(176), "UTF-8", "ASCII");
    $message = ""
      . "**Weather Report:** " . $report->location . "\n"
      . "**Condition:** "
        . $report->condition[1] . ", "
        . number_format($report->condition[0]) . $deg
        . $report->unit_temperature . " "
      . (!is_null($report->wind_chill) ?
        "**Wind Chill:** "
        . number_format($report->wind_chill) . $deg
        . $report->unit_temperature : "")
      . (!is_null($report->heat_index) ?
        "**Heat Index:** "
        . number_format($report->heat_index) . $deg
        . $report->unit_temperature : "")
      . "\n"
      . "**Humidity:** "
        . $report->humidity . "% "
      . "**Wind:** "
        . ($report->wind_speed != 0 ? ""
        . self::directionString($report->wind_direction, true)
        . " at " : "")
        . $report->wind_speed
        . " "
        . $report->unit_speed . "\n"
      . (!empty($report->sunrise) &&
         !empty($report->sunset) ? ""
      . "**Sunrise:** "
        . $report->sunrise . " "
      . "**Sunset:** "
        . $report->sunset . "\n" : "")
    ;
    return $message;
  }

  public function getAsMessage(&$webhook_post_data) {
    $message          = new StdClass();
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
      . (!is_null($report->wind_chill) ?
        "Wind Chill: "
        . number_format($report->wind_chill) . $deg
        . $report->unit_temperature : "")
      . (!is_null($report->heat_index) ?
        "Heat Index: "
        . number_format($report->heat_index) . $deg
        . $report->unit_temperature : "")
      . "\n"
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
