<?php

namespace CarlBennett\API\Controllers\Software;

use \CarlBennett\API\Libraries\Core\HTTPCode;

class Update extends \CarlBennett\API\Controllers\Base
{
    public const UPDATE_STATUS_BAD_REQUEST        = ['code' => 0, 'message' => 'Bad request'];
    public const UPDATE_STATUS_PRODUCT_UNKNOWN    = ['code' => 1, 'message' => 'Unknown product'];
    public const UPDATE_STATUS_PRODUCT_DEPRECATED = ['code' => 2, 'message' => 'Deprecated product'];
    public const UPDATE_STATUS_VERSION_UNKNOWN    = ['code' => 3, 'message' => 'Version is unknown'];
    public const UPDATE_STATUS_VERSION_OLD        = ['code' => 4, 'message' => 'Version is old'];
    public const UPDATE_STATUS_VERSION_CURRENT    = ['code' => 5, 'message' => 'Version is current'];

    public function __construct()
    {
        $this->model = new \CarlBennett\API\Models\Software\Update();
    }

    public function invoke(?array $args): bool
    {
        $this->model->_responseCode = HTTPCode::BAD_REQUEST;
        $this->model->result = [];
        $result = &$this->model->result;

        $result['status'] = self::UPDATE_STATUS_BAD_REQUEST;
        $result['update'] = null;

        $data = \CarlBennett\API\Libraries\Core\Router::query();
        $result['product'] = $data['product'] ?? null;
        $result['version'] = $data['version'] ?? null;

        if (\is_null($result['product']) || empty($result['product']))
        {
            return true;
        }

        $product_found = false;
        $config = \CarlBennett\API\Libraries\Core\Config::instance()->root;
        foreach ($config['software']['products'] as $product)
        {
            if (\strtolower($product['name']) == \strtolower($result['product']))
            {
                $product_found = true;
                $this->model->_responseCode = HTTPCode::OK;

                $new_version = null;
                foreach ($product['versions'] as $version)
                {
                    if (\version_compare($result['version'], $version['version'], '<'))
                    {
                        $new_version = $version;
                    }
                }

                if (!\is_null($new_version))
                {
                    $result['status'] = self::UPDATE_STATUS_VERSION_OLD;

                    $result['update'] = [];
                    $result['update']['url'] = $version['url'];
                    $result['update']['version'] = $version['version'];
                }
                else
                {
                    $result['status'] = self::UPDATE_STATUS_VERSION_CURRENT;
                }

                break;
            }
        }

        if (!$product_found)
        {
            $this->model->_responseCode = HTTPCode::NOT_FOUND;
            $result['status'] = self::UPDATE_STATUS_PRODUCT_UNKNOWN;
        }

        return true;
    }
}
