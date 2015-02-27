<?php

namespace CarlBennett\API\Views;

use CarlBennett\API\Libraries\Common;
use CarlBennett\API\Libraries\Exceptions\IncorrectModelException;
use CarlBennett\API\Libraries\Model;
use CarlBennett\API\Libraries\View;
use CarlBennett\API\Libraries\WeatherReport;
use CarlBennett\API\Models\Slack as SlackModel;

class SlackMarkdown extends View {

  public function getMimeType() {
    return "text/x-markdown;charset=utf-8";
  }

  public function render(Model &$model) {
    if (!$model instanceof SlackModel) {
      throw new IncorrectModelException();
    }
    if (!$model->result[0]) {
      echo $model->result[1];
    } else if ($model->result[1] instanceof WeatherReport) {
      echo $model->result[1]->getAsMarkdown();
    }
  }

}
