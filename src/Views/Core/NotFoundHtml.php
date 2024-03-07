<?php

namespace CarlBennett\API\Views\Core;

class NotFoundHtml extends \CarlBennett\API\Views\Base\Html
{
    public static function invoke(\CarlBennett\API\Interfaces\Model $model): void
    {
        if (!$model instanceof \CarlBennett\API\Models\Core\NotFound)
        {
            throw new \CarlBennett\API\Exceptions\InvalidModelException($model);
        }

        echo '<!DOCTYPE html>' . \PHP_EOL;
        echo '<html><head>' . \PHP_EOL;
        echo '<title>Not Found</title>' . \PHP_EOL;
        echo '</head><body>' . \PHP_EOL;
        echo '<h1>Not Found</h1>' . \PHP_EOL;
        echo '</body></html>' . \PHP_EOL;

        $model->_responseHeaders['Content-Type'] = self::mimeType();
    }
}
