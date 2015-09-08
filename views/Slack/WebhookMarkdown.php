<?php

namespace CarlBennett\API\Views\Slack;

use \CarlBennett\API\Libraries\Common;
use \CarlBennett\API\Libraries\Exceptions\IncorrectModelException;
use \CarlBennett\API\Libraries\Exceptions\UnhandledInternalResponseException;
use \CarlBennett\API\Libraries\Model;
use \CarlBennett\API\Libraries\View;
use \CarlBennett\API\Libraries\WeatherReport;
use \CarlBennett\API\Models\Slack\Webhook as SlackWebhookModel;

class WebhookMarkdown extends View {

  public function getMimeType() {
    return "text/x-markdown;charset=utf-8";
  }

  public function render(Model &$model) {
    if (!$model instanceof SlackWebhookModel) {
      throw new IncorrectModelException();
    }
    if (is_string($model->result)) {
      echo $model->result;
    } else if ($model->result instanceof WeatherReport) {
      echo $model->result->getAsMarkdown();
    } else {
      throw new UnhandledInternalResponseException();
    }
  }

}
