<?php

namespace CarlBennett\API\Views\Slack;

use \CarlBennett\API\Models\Slack\Webhook as WebhookModel;
use \CarlBennett\MVC\Libraries\Common;
use \CarlBennett\MVC\Libraries\Exceptions\IncorrectModelException;
use \CarlBennett\MVC\Libraries\Model;
use \CarlBennett\MVC\Libraries\View;

class WebhookMarkdown extends View {
  public function getMimeType() {
    return 'text/x-markdown;charset=utf-8';
  }

  public function render( Model &$model ) {
    if ( !$model instanceof WebhookModel ) {
      throw new IncorrectModelException();
    }
    echo $model->result;
  }
}
