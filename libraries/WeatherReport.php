<?php

namespace CarlBennett\API\Libraries;

use CarlBennett\API\Libraries\Common;

class WeatherReport {

  protected $location;

  public function __construct($location) {
    $this->location = $location;
  }

  public static function directionString($degree) {
    if ($degree >= 0   && $degree < 45 ) return "N";
    if ($degree >= 45  && $degree < 90 ) return "NE";
    if ($degree >= 90  && $degree < 135) return "E";
    if ($degree >= 135 && $degree < 180) return "SE";
    if ($degree >= 180 && $degree < 225) return "S";
    if ($degree >= 225 && $degree < 270) return "SW";
    if ($degree >= 270 && $degree < 315) return "W";
    if ($degree >= 315 && $degree < 360) return "NW";
    return "?";
  }

  public function getAsMessage(&$webhook_post_data) {
    $message = new \StdClass();
    if ($this->location == 666) {
      $message->report = null;
      $message->format = "html";
      $message->message = "<b>Weather Report:</b> Hell<br/><b>Condition:</b> Blistering Hot, 10,000,000,000&deg;F <b>Wind Chill:</b> n/a<br/><b>Humidity:</b> 0% <b>Wind:</b> " . mt_rand(0, 6) . " mph<br/><b>Sunrise:</b> n/a <b>Sunset:</b> n/a<br/>";
      $message->color = "gray";
    } else {
      $message->report = $this->downloadReport();
      $message->format = "html";
      if ($message->report->query->count >= 1 && !is_null($message->report->query->results)) {
        $message->color = "gray";
        $message->message = "<b>Weather Report:</b> "
          . str_replace(", ,", ",", ($message->report->query->results->channel->location->city . ", " . $message->report->query->results->channel->location->region . ", " . $message->report->query->results->channel->location->country)) . "<br/>"
          . "<b>Condition:</b> " . $message->report->query->results->channel->item->condition->text . ", " . $message->report->query->results->channel->item->condition->temp . "&deg;" . $message->report->query->results->channel->units->temperature . " "
          . "<b>Wind Chill:</b> " . $message->report->query->results->channel->wind->chill . "&deg;" . $message->report->query->results->channel->units->temperature . "<br/>"
          . "<b>Humidity:</b> " . $message->report->query->results->channel->atmosphere->humidity . "% "
          . "<b>Wind:</b> " . self::directionString($message->report->query->results->channel->wind->direction) . " at " . $message->report->query->results->channel->wind->speed . " " . $message->report->query->results->channel->units->speed . "<br/>"
          . "<b>Sunrise:</b> " . $message->report->query->results->channel->astronomy->sunrise . " "
          . "<b>Sunset:</b> " . $message->report->query->results->channel->astronomy->sunset . "<br/>"
        ;
      } else {
        $message->color = "red";
        $their_name = $webhook_post_data->item->message->from->name;
        switch (mt_rand(0, 3)) {
          case 0: $message->message = "Hey now, where do you live, $their_name? Is it even in the Milky Way?"; break;
          case 1: $message->message = "I don't even."; break;
          case 2: $message->message = "What do I look like? A mind reader?"; break;
          default: $message->message = "<b>Error:</b> Cannot retrieve weather information.";
        }
      }
    }
    return $message;
  }

  public function downloadReport() {
    $query = "select * from weather.forecast where woeid in (select woeid from geo.places(1) where text=\""
      . filter_var($this->location, FILTER_UNSAFE_RAW)
      . "\")";
    $url = "https://query.yahooapis.com/v1/public/yql?q=" . urlencode($query) . "&format=json&env=store://datatables.org/alltableswithkeys";
    $response = Common::curlRequest($url, null);
    if (!$response || $response->code != 200 || empty($response->data)) {
      // Not a valid response
      return false;
    }
    if (stripos($response->type, "application/json") === false && stripos($response->type, "text/json") === false) {
      // Not expected JSON mime-type
      return $response->type;
    }
    return json_decode($response->data);
  }

}
