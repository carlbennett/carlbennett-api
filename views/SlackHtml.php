<?php

namespace CarlBennett\API\Views;

use CarlBennett\API\Libraries\Common;
use CarlBennett\API\Libraries\Exceptions\IncorrectModelException;
use CarlBennett\API\Libraries\Model;
use CarlBennett\API\Libraries\View;
use CarlBennett\API\Models\Slack as SlackModel;

class SlackHtml extends View {

  public function getMimeType() {
    return "text/html;charset=utf-8";
  }

  public function render(Model &$model) {
    if (!$model instanceof SlackModel) {
      throw new IncorrectModelException();
    }
    echo $model->result[1]->getAsHtml();
  }

}
