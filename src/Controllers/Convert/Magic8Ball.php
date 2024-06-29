<?php

namespace CarlBennett\API\Controllers\Convert;

use \CarlBennett\API\Libraries\Core\HTTPCode;

class Magic8Ball extends \CarlBennett\API\Controllers\Base
{
    public function __construct()
    {
        $this->model = new \CarlBennett\API\Models\Convert\MixedInOut();
    }

    public function invoke(?array $args): bool
    {
        $magic8ball = new \CarlBennett\API\Libraries\Fun\Magic8Ball();

        $query = \CarlBennett\API\Libraries\Core\Router::query();
        $this->model->error = !isset($query['input']) || !\is_string($query['input']);
        $this->model->input = $query['input'] ?? null;
        $this->model->output = $magic8ball->getPrediction($this->model->input ?? '');
        $this->model->_responseCode = $this->model->error ? HTTPCode::BAD_REQUEST : HTTPCode::OK;
        return true;
    }
}
