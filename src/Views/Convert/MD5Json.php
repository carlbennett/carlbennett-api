<?php

namespace CarlBennett\API\Views\Convert;

class MD5Json extends \CarlBennett\API\Views\Base\Json
{
    public static function invoke(\CarlBennett\API\Interfaces\Model $model): void
    {
        if (!$model instanceof \CarlBennett\API\Models\Convert\StringInOut)
        {
            throw new \CarlBennett\API\Exceptions\InvalidModelException($model);
        }

        echo \json_encode($model, self::jsonFlags()) . \PHP_EOL;
        $model->_responseHeaders['Content-Type'] = self::mimeType();
    }
}
