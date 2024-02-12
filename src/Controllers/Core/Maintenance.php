<?php

namespace CarlBennett\API\Controllers\Core;

class Maintenance extends \CarlBennett\API\Controllers\Base
{
    public function __construct()
    {
        $this->model = new \CarlBennett\API\Models\Core\Maintenance();
    }

    public function invoke(?array $args): bool
    {
        $this->model->message = \array_shift($args);
        $this->model->_responseCode = \CarlBennett\API\Libraries\Core\HTTPCode::SERVICE_UNAVAILABLE;
        return true;
    }
}
