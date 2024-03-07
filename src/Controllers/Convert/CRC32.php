<?php

namespace CarlBennett\API\Controllers\Convert;

use \CarlBennett\API\Libraries\Core\HTTPCode;

class CRC32 extends \CarlBennett\API\Controllers\Base
{
    public function __construct()
    {
        $this->model = new \CarlBennett\API\Models\Convert\StringInOut();
    }

    public function invoke(?array $args): bool
    {
        $query = \CarlBennett\API\Libraries\Core\Router::query();
        $this->model->error = !isset($query['input']) || !\is_string($query['input']);
        $this->model->input = $query['input'] ?? null;
        $this->model->output = \hash('crc32', $this->model->input ?? '');
        $this->model->_responseCode = $this->model->error ? HTTPCode::BAD_REQUEST : HTTPCode::OK;
        return true;
    }
}
