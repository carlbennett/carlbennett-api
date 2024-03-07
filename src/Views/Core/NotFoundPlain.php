<?php

namespace CarlBennett\API\Views\Core;

class NotFoundPlain extends \CarlBennett\API\Views\Base\Plain
{
    public static function invoke(\CarlBennett\API\Interfaces\Model $model): void
    {
        if (!$model instanceof \CarlBennett\API\Models\Core\NotFound)
        {
            throw new \CarlBennett\API\Exceptions\InvalidModelException($model);
        }

        echo $model->_responseCode . ' Not Found' . \PHP_EOL;
        $model->_responseHeaders['Content-Type'] = self::mimeType();
    }
}
