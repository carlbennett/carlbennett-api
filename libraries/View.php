<?php

namespace CarlBennett\API\Libraries;

use CarlBennett\API\Libraries\Model;

abstract class View {

  public abstract function getMimeType();
  public abstract function render(Model &$model);

}
