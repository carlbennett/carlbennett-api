<?php
/**
 *  carlbennett-api, a PHP-based API framework designed by @carlbennett
 *  Copyright (C) 2015-2024 Carl Bennett
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

use \CarlBennett\API\Libraries\Core\Config;
use \CarlBennett\API\Libraries\Core\Router;

function main(int $argc, array $argv): int
{
    if (\version_compare(\phpversion(), '8.1', '<'))
    {
        \http_response_code(500);
        echo 'Minimum supported PHP version is 8.1, detected version: ' . \phpversion() . \PHP_EOL;
        exit(1);
    }

    if (!\file_exists(__DIR__ . '/../lib/autoload.php') || !\filesize(__DIR__ . '/../lib/autoload.php'))
    {
        \http_response_code(500);
        echo 'Autoloader is missing, run `composer install`'.\PHP_EOL;
        exit(1);
    }
    require(__DIR__ . '/../lib/autoload.php');

    \date_default_timezone_set('Etc/UTC');
    \CarlBennett\API\Libraries\Core\Logger::registerAPMs();
    \CarlBennett\API\Libraries\Core\GlobalErrorHandler::createOverrides();

    if (\php_sapi_name() == 'cli')
    {
        return \CarlBennett\API\Libraries\CLI\Handler::invoke($argc, $argv);
    }
    else
    {
        Router::$route_not_found = ['Core\\NotFound', ['Core\\NotFoundHtml', 'Core\\NotFoundJson', 'Core\\NotFoundPlain']];
        $maintenance = Config::instance()->root['router']['maintenance'] ?? true;
        if ((\is_bool($maintenance) && $maintenance) || (isset($maintenance[0]) && \is_bool($maintenance[0]) && $maintenance[0]))
        {
            $message = isset($maintenance[1]) && \is_string($maintenance[1]) && !empty($maintenance[1]) ? $maintenance[1] : null;
            Router::$routes = [
                ['#.*#', 'Core\\Maintenance', ['Core\\MaintenanceHtml', 'Core\\MaintenanceJson', 'Core\\MaintenancePlain'], $message],
            ];
            Router::invoke();
            return \CarlBennett\API\Libraries\CLI\Handler::INVOKE_FAILED;
        }
        else
        {
            $Core_Redirect_Views = ['Core\\RedirectHtml', 'Core\\RedirectJson', 'Core\\RedirectPlain'];
            Router::$routes = [
                ['#^/$#', 'Core\\Redirect', $Core_Redirect_Views, '/status.txt'],
                ['#^/\.well-known\/change-password$#', 'Core\\Redirect', $Core_Redirect_Views, '/user/change-password'],
                ['#^/convert/chartoint(?:\.json)?$#', 'Convert\\CharToInt', ['Convert\\CharToIntJson']],
                ['#^/convert/crc32(?:\.json)?$#', 'Convert\\CRC32', ['Convert\\CRC32Json']],
                ['#^/convert/inttochar(?:\.json)?$#', 'Convert\\IntToChar', ['Convert\\IntToCharJson']],
                ['#^/convert/magic8ball(?:\.json)?$#', 'Convert\\Magic8Ball', ['Convert\\Magic8BallJson']],
                ['#^/convert/md5(?:\.json)?$#', 'Convert\\MD5', ['Convert\\MD5Json']],
                ['#^/convert/sha1(?:\.json)?$#', 'Convert\\SHA1', ['Convert\\SHA1Json']],
                ['#^/convert/sha256(?:\.json)?$#', 'Convert\\SHA256', ['Convert\\SHA256Json']],
                ['#^/convert/sha384(?:\.json)?$#', 'Convert\\SHA384', ['Convert\\SHA384Json']],
                ['#^/convert/sha512(?:\.json)?$#', 'Convert\\SHA512', ['Convert\\SHA512Json']],
                ['#^/convert/urldecode(?:\.json)?$#', 'Convert\\UrlDecode', ['Convert\\UrlDecodeJson']],
                ['#^/convert/urlencode(?:\.json)?$#', 'Convert\\UrlEncode', ['Convert\\UrlEncodeJson']],
                ['#^/slack/webhook(?:\.md)?$#', 'Slack\\Webhook', ['Slack\\WebhookMarkdown']],
                ['#^/software/update(?:\.json)?$#', 'Software\\Update', ['Software\\UpdateJson']],
                ['#^/software/verifylicense(?:\.json)?$#', 'Software\\VerifyLicense', ['Software\\VerifyLicenseJson']],
                ['#^/status$#', 'Core\\Status', ['Core\\StatusJson', 'Core\\StatusPlain']],
                ['#^/status/$#', 'Core\\Redirect', $Core_Redirect_Views, '/status'],
                ['#^/status\.json$#', 'Core\\Status', ['Core\\StatusJson']],
                ['#^/status\.txt$#', 'Core\\Status', ['Core\\StatusPlain']],
                ['#^/user/change-password(?:\.json)?$#', 'User\\ChangePassword', ['User\\ChangePasswordJson']],
            ];
            Router::invoke();
            return \CarlBennett\API\Libraries\CLI\Handler::INVOKE_SUCCESS;
        }
    }
}

exit(main($argc ?? 0, $argv ?? []));
