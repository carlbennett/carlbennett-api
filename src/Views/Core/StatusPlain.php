<?php

namespace CarlBennett\API\Views\Core;

class StatusPlain extends \CarlBennett\API\Views\Base\Plain
{
    public static function invoke(\CarlBennett\API\Interfaces\Model $model): void
    {
        if (!$model instanceof \CarlBennett\API\Models\Core\Status)
        {
            throw new \CarlBennett\API\Exceptions\InvalidModelException($model);
        }

        echo \CarlBennett\API\Libraries\Core\ArrayFlattener::flatten($model->status) . \PHP_EOL;
        $model->_responseHeaders['Content-Type'] = self::mimeType();
    }
}
