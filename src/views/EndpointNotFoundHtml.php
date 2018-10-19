<?php

namespace CarlBennett\API\Views;

use \CarlBennett\API\Models\EndpointNotFound as EndpointNotFoundModel;
use \CarlBennett\MVC\Libraries\Exceptions\IncorrectModelException;
use \CarlBennett\MVC\Libraries\Model;
use \CarlBennett\MVC\Libraries\View;

class EndpointNotFoundHtml extends View {
  public function getMimeType() {
    return 'text/html;charset=utf-8';
  }

  public function render(Model &$model) {
    if ( !$model instanceof EndpointNotFoundModel ) {
      throw new IncorrectModelException();
    }
    echo "<!DOCTYPE html>\n" . '<html><head>' .
      '<title>Endpoint Not Found</title>' .
      '</head><body><p>' .
      "Carl Bennett's API could not find the endpoint or resource requested." .
      '</p><p><a href="javascript:history.go(-1);">Go Back</a></p>' .
      '</body></html>';
  }
}
