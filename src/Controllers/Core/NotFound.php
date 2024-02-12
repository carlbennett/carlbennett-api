<?php

namespace CarlBennett\API\Controllers\Core;

class NotFound extends \CarlBennett\API\Controllers\Base
{
    public function __construct()
    {
        $this->model = new \CarlBennett\API\Models\Core\NotFound();
    }

    public function invoke(?array $args): bool
    {
        $this->model->_responseCode = \CarlBennett\API\Libraries\Core\HTTPCode::NOT_FOUND;
        return true;
    }
}
