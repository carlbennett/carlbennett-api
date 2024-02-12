<?php

namespace CarlBennett\API\Controllers\Core;

use \CarlBennett\API\Libraries\Core\HTTPCode;
use \CarlBennett\API\Libraries\Core\VersionInfo;

class Status extends \CarlBennett\API\Controllers\Base
{
    public function __construct()
    {
        $this->model = new \CarlBennett\API\Models\Core\Status();
    }

    public function invoke(?array $args): bool
    {
        $this->model->status = [];
        $status = &$this->model->status;

        $status['healthcheck'] = [];
        $status['healthcheck']['database'] = \CarlBennett\API\Libraries\Db\Database::instance() ? true : false;

        $healthy = true;
        foreach ($status['healthcheck'] as &$v)
        {
            if (!$v)
            {
                $healthy = false;
                break;
            }
        }
        $status['healthcheck']['healthy'] = $healthy;
        \ksort($status['healthcheck']);

        $user_agent = \getenv('HTTP_USER_AGENT');
        $remote_addr = \getenv('REMOTE_ADDR');

        $status['is_browser'] = VersionInfo::isBrowser($user_agent);
        $status['remote_address'] = $remote_addr;
        $status['remote_geoinfo'] = \CarlBennett\API\Libraries\Core\GeoIP::getRecord($remote_addr);
        $status['remote_user_agent'] = $user_agent;
        $status['timestamp'] = new \CarlBennett\API\Libraries\Core\DateTimeImmutable('now');
        $status['version_info'] = VersionInfo::get();

        $this->model->_responseCode = $healthy ? HTTPCode::OK : HTTPCode::INTERNAL_SERVER_ERROR;
        return true;
    }
}
