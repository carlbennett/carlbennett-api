<?php

namespace CarlBennett\API\Controllers\Convert;

use \CarlBennett\API\Libraries\Core\HTTPCode;

class IntToChar extends \CarlBennett\API\Controllers\Base
{
    public function __construct()
    {
        $this->model = new \CarlBennett\API\Models\Convert\MixedInOut();
    }

    public function invoke(?array $args): bool
    {
        $query = \CarlBennett\API\Libraries\Core\Router::query();
        $this->model->input = !isset($query['input']) || !\is_numeric($query['input']) ? null : \max(0, (int) $query['input']);
        $this->model->output = \mb_chr($this->model->input ?? 0, 'UTF-8');
        $this->model->error = $this->model->output === false || !isset($query['input']) || !\is_numeric($query['input']);
        $this->model->_responseCode = $this->model->error ? HTTPCode::BAD_REQUEST : HTTPCode::OK;
        return true;
    }
}
