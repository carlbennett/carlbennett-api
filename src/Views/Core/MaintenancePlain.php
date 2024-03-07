<?php

namespace CarlBennett\API\Views\Core;

class MaintenancePlain extends \CarlBennett\API\Views\Base\Plain
{
    public static function invoke(\CarlBennett\API\Interfaces\Model $model): void
    {
        if (!$model instanceof \CarlBennett\API\Models\Core\Maintenance)
        {
            throw new \CarlBennett\API\Exceptions\InvalidModelException($model);
        }

        $message = $model->message ?? "Carl Bennett's API is temporarily offline, check back later.";
        echo $model->_responseCode . ' Service Unavailable' . \PHP_EOL . $message . \PHP_EOL;
        $model->_responseHeaders['Content-Type'] = self::mimeType();
    }
}
