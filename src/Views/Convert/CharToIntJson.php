<?php

namespace CarlBennett\API\Views\Convert;

class CharToIntJson extends \CarlBennett\API\Views\Base\Json
{
    public static function invoke(\CarlBennett\API\Interfaces\Model $model): void
    {
        if (!$model instanceof \CarlBennett\API\Models\Convert\MixedInOut)
        {
            throw new \CarlBennett\API\Exceptions\InvalidModelException($model);
        }

        echo \json_encode($model, self::jsonFlags()) . \PHP_EOL;
        $model->_responseHeaders['Content-Type'] = self::mimeType();
    }
}
