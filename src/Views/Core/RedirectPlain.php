<?php

namespace CarlBennett\API\Views\Core;

class RedirectPlain extends \CarlBennett\API\Views\Base\Plain
{
    public static function invoke(\CarlBennett\API\Interfaces\Model $model): void
    {
        if (!$model instanceof \CarlBennett\API\Models\Core\Redirect)
        {
            throw new \CarlBennett\API\Exceptions\InvalidModelException($model);
        }

        echo $model->_responseCode . ' Redirect' . \PHP_EOL . $model->location . \PHP_EOL;
        $model->_responseHeaders['Content-Type'] = self::mimeType();
    }
}
