<?php

namespace CarlBennett\API\Views\Core;

class MaintenanceHtml extends \CarlBennett\API\Views\Base\Html
{
    public static function invoke(\CarlBennett\API\Interfaces\Model $model): void
    {
        if (!$model instanceof \CarlBennett\API\Models\Core\Maintenance)
        {
            throw new \CarlBennett\API\Exceptions\InvalidModelException($model);
        }

        $message = $model->message ?? "Carl Bennett's API is temporarily offline, check back later.";
        $message = \filter_var($message, \FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        echo '<!DOCTYPE html>' . \PHP_EOL;
        echo '<html><head>' . \PHP_EOL;
        echo '<title>Maintenance</title>' . \PHP_EOL;
        echo '</head><body>' . \PHP_EOL;
        echo '<h1>Maintenance</h1>' . \PHP_EOL;
        echo '<p>' . $message . '</p>' . \PHP_EOL;
        echo '<hr/><address>' . \json_encode(\CarlBennett\API\Libraries\Core\VersionInfo::get()) . '</address>' . \PHP_EOL;
        echo '</body></html>' . \PHP_EOL;

        $model->_responseHeaders['Content-Type'] = self::mimeType();
    }
}
