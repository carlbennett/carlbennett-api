<?php

namespace CarlBennett\API\Views\Core;

class MaintenanceJson extends \CarlBennett\API\Views\Base\Json
{
    public static function invoke(\CarlBennett\API\Interfaces\Model $model): void
    {
        if (!$model instanceof \CarlBennett\API\Models\Core\Maintenance)
        {
            throw new \CarlBennett\API\Exceptions\InvalidModelException($model);
        }

        $message = $model->message ?? "Carl Bennett's API is temporarily offline, check back later.";
        echo \json_encode([$model->_responseCode, 'Service Unavailable', $message], self::jsonFlags()) . \PHP_EOL;
        $model->_responseHeaders['Content-Type'] = self::mimeType();
    }
}
