<?php
/**
 *  carlbennett-api, a PHP-based API framework designed by @carlbennett
 *  Copyright (C) 2015-2016  Carl Bennett
 *  This file is part of carlbennett-api.
 *
 *  carlbennett-api is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  carlbennett-api is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with carlbennett-api.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace CarlBennett\API;

use \CarlBennett\API\Libraries\Cache;
use \CarlBennett\API\Libraries\Common;
use \CarlBennett\API\Libraries\Exceptions\APIException;
use \CarlBennett\API\Libraries\Exceptions\ClassNotFoundException;
use \CarlBennett\API\Libraries\Router;
use \CarlBennett\API\Libraries\VersionInfo;
use \CarlBennett\MVC\Libraries\Common as CommonMVCLib;
use \CarlBennett\MVC\Libraries\DatabaseDriver;
use \CarlBennett\MVC\Libraries\GlobalErrorHandler;
use \CarlBennett\MVC\Libraries\Logger;
use \ReflectionClass;

function main() {

    if (!file_exists(__DIR__ . '/../lib/autoload.php')) {
        http_response_code(500);
        exit('Server misconfigured. Please run `composer install`.');
    }
    require(__DIR__ . "/../lib/autoload.php");

    #GlobalErrorHandler::createOverrides();

    Logger::initialize();

    Common::$config = json_decode(
        file_get_contents(__DIR__ . "/../etc/config.json")
    );

    VersionInfo::$version = VersionInfo::get();

    Common::$cache = new Cache();

    CommonMVCLib::$database = null;

    DatabaseDriver::$character_set = Common::$config->MySQL->character_set;
    DatabaseDriver::$database_name = Common::$config->MySQL->database;
    DatabaseDriver::$password      = Common::$config->MySQL->password;
    DatabaseDriver::$servers       = Common::$config->MySQL->servers;
    DatabaseDriver::$timeout       = Common::$config->MySQL->timeout;
    DatabaseDriver::$username      = Common::$config->MySQL->username;

    $router = new Router();
    $router->route();
    $router->send();

}

main();
