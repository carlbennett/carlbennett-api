<?php

namespace CarlBennett\API\Views\Core;

class RedirectHtml extends \CarlBennett\API\Views\Base\Html
{
    public static function invoke(\CarlBennett\API\Interfaces\Model $model): void
    {
        if (!$model instanceof \CarlBennett\API\Models\Core\Redirect)
        {
            throw new \CarlBennett\API\Exceptions\InvalidModelException($model);
        }

        $url = \filter_var($model->location, \FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        echo '<!DOCTYPE html>' . \PHP_EOL;
        echo '<html><head>' . \PHP_EOL;
        echo '<title>Redirect</title>' . \PHP_EOL;
        echo '<meta http-equiv="Refresh" content="' . $url . '"/>' . \PHP_EOL;
        echo '</head><body>' . \PHP_EOL;
        echo '<h1>Redirect</h1>' . \PHP_EOL;
        echo '<a href="' . $url . '">' . $url . '</a>' . \PHP_EOL;
        echo '</body></html>' . \PHP_EOL;

        $model->_responseHeaders['Content-Type'] = self::mimeType();
    }
}
