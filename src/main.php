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

use \CarlBennett\API\Libraries\Exceptions\APIException;
use \CarlBennett\API\Libraries\Exceptions\ClassNotFoundException;
use \CarlBennett\API\Libraries\VersionInfo;
use \CarlBennett\MVC\Libraries\Cache;
use \CarlBennett\MVC\Libraries\Common;
use \CarlBennett\MVC\Libraries\DatabaseDriver;
use \CarlBennett\MVC\Libraries\GlobalErrorHandler;
use \CarlBennett\MVC\Libraries\Logger;
use \CarlBennett\MVC\Libraries\Router;
use \ReflectionClass;

function main() {

  if (!file_exists(__DIR__ . '/../lib/autoload.php')) {
      http_response_code(500);
      exit('Server misconfigured. Please run `composer install`.');
  }
  require(__DIR__ . '/../lib/autoload.php');

  #GlobalErrorHandler::createOverrides();

  date_default_timezone_set('Etc/UTC');

  Logger::initialize();

  Common::$config = json_decode(file_get_contents(
      __DIR__ . '/../etc/config.json'
  ));

  VersionInfo::$version = VersionInfo::get();

  Common::$cache = new Cache(
    Common::$config->Memcache->servers,
    Common::$config->Memcache->connect_timeout,
    Common::$config->Memcache->tcp_nodelay
  );

  Common::$database = null;

  DatabaseDriver::$character_set = Common::$config->MySQL->character_set;
  DatabaseDriver::$database_name = Common::$config->MySQL->database;
  DatabaseDriver::$password      = Common::$config->MySQL->password;
  DatabaseDriver::$servers       = Common::$config->MySQL->servers;
  DatabaseDriver::$timeout       = Common::$config->MySQL->timeout;
  DatabaseDriver::$username      = Common::$config->MySQL->username;

  $router = new Router(
    'CarlBennett\\API\\Controllers\\',
    'CarlBennett\\API\\Views\\'
  );

  if ( Common::$config->Router->maintenance ) {
    $router->addRoute( // URL: *
      '#.*#', 'Maintenance', 'MaintenanceHtml'
    );
  } else {
    $router->addRoute( // URL: /
      '#^/$#', 'RedirectSoft', 'RedirectSoftHtml', '/status.txt'
    );
    $router->addRoute( // URL: /slack/webhook OR /slack/webhook.md
      '#^/slack/webhook(?:\.md)?/?$#',
      'Slack\\Webhook', 'Slack\\WebhookMarkdown'
    );
    $router->addRoute( // URL: /software/update OR /software/update.json
      '#^/software/update(?:\.json)?/?$#',
      'Software\\Update', 'Software\\UpdateJSON'
    );
    $router->addRoute(
      // URL: /software/verifylicense OR /software/verifylicense.json
      '#^/software/verifylicense(?:\.json)?/?$#',
      'Software\\VerifyLicense', 'Software\\VerifyLicenseJSON'
    );
    $router->addRoute( // URL: /status
      '#^/status/?$#', 'RedirectSoft', 'RedirectSoftHtml', '/status.json'
    );
    $router->addRoute( // URL: /status.json
      '#^/status\.json/?$#', 'Status', 'StatusJSON'
    );
    $router->addRoute( // URL: /status.txt
      '#^/status\.txt/?$#', 'Status', 'StatusPlain'
    );
    $router->addRoute( // URL: /weather OR /weather.json
      '#^/weather(?:\.json)?/?$#', 'Weather', 'WeatherJSON'
    );
    $router->addRoute( // URL: /weather.md
      '#^/weather\.md/?$#', 'Weather', 'WeatherMarkdown'
    );
    $router->addRoute( // URL: /weather.txt
      '#^/weather\.txt/?$#', 'Weather', 'WeatherPlain'
    );
    $router->addRoute( // URL: *
      '#.*#', 'EndpointNotFound', 'EndpointNotFoundHtml'
    );
  }

  $router->route();
  $router->send();

}

main();
