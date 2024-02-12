<?php

namespace CarlBennett\API\Controllers\Core;

class Redirect extends \CarlBennett\API\Controllers\Base
{
    public function __construct()
    {
        $this->model = new \CarlBennett\API\Models\Core\Redirect();
    }

    public function invoke(?array $args): bool
    {
        $this->model->location = \array_shift($args);
        $this->model->_responseCode = \CarlBennett\API\Libraries\Core\HTTPCode::FOUND;
        $this->model->_responseHeaders['Location'] = $this->model->location;
        return true;
    }
}
