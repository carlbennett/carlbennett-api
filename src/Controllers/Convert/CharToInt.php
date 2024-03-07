<?php

namespace CarlBennett\API\Controllers\Convert;

use \CarlBennett\API\Libraries\Core\HTTPCode;

class CharToInt extends \CarlBennett\API\Controllers\Base
{
    public function __construct()
    {
        $this->model = new \CarlBennett\API\Models\Convert\MixedInOut();
    }

    public function invoke(?array $args): bool
    {
        $query = \CarlBennett\API\Libraries\Core\Router::query();
        $this->model->input = !isset($query['input']) ? null : \mb_substr((string) $query['input'], 0, 1);
        $this->model->error = !\is_string($query['input']) || empty($query['input']);
        $this->model->output = empty($this->model->input) ? null : \mb_ord($this->model->input ?? '', 'UTF-8');
        $this->model->_responseCode = $this->model->error ? HTTPCode::BAD_REQUEST : HTTPCode::OK;
        return true;
    }
}
