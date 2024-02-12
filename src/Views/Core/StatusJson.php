<?php

namespace CarlBennett\API\Views\Core;

class StatusJson extends \CarlBennett\API\Views\Base\Json
{
    public static function invoke(\CarlBennett\API\Interfaces\Model $model): void
    {
        if (!$model instanceof \CarlBennett\API\Models\Core\Status)
        {
            throw new \CarlBennett\API\Exceptions\InvalidModelException($model);
        }

        echo \json_encode($model->status, self::jsonFlags()) . \PHP_EOL;
        $model->_responseHeaders['Content-Type'] = self::mimeType();
    }
}
