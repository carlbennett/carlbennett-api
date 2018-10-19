<?php

namespace CarlBennett\API\Views;

use \CarlBennett\API\Models\RedirectSoft as RedirectSoftModel;
use \CarlBennett\MVC\Libraries\Exceptions\IncorrectModelException;
use \CarlBennett\MVC\Libraries\Model;
use \CarlBennett\MVC\Libraries\View;

class RedirectSoftHtml extends View {
  public function getMimeType() {
    return 'text/html;charset=utf-8';
  }

  public function render( Model &$model ) {
    if ( !$model instanceof RedirectSoftModel ) {
      throw new IncorrectModelException();
    }
    echo "<!DOCTYPE html>\n" . '<html><head><title>Redirect Soft</title>' .
      '<meta http-equiv="Refresh" content="' . $model->location . '"/>' .
      '</head><body><p>Redirecting to <a href="' .
      filter_var( $model->location, FILTER_SANITIZE_FULL_SPECIAL_CHARS ) .
      '">' .
      filter_var( $model->location, FILTER_SANITIZE_FULL_SPECIAL_CHARS ) .
      '</a>...</p></body></html>';
  }
}
