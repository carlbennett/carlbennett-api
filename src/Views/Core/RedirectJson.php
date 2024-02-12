<?php

namespace CarlBennett\API\Views\Core;

class RedirectJson extends \CarlBennett\API\Views\Base\Json
{
    public static function invoke(\CarlBennett\API\Interfaces\Model $model): void
    {
        if (!$model instanceof \CarlBennett\API\Models\Core\Redirect)
        {
            throw new \CarlBennett\API\Exceptions\InvalidModelException($model);
        }

        echo \json_encode([$model->_responseCode, 'Redirect', $model->location], self::jsonFlags()) . \PHP_EOL;
        $model->_responseHeaders['Content-Type'] = self::mimeType();
    }
}
