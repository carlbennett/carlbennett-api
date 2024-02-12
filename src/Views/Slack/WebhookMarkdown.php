<?php

namespace CarlBennett\API\Views\Slack;

class WebhookMarkdown extends \CarlBennett\API\Views\Base\Markdown
{
    public static function invoke(\CarlBennett\API\Interfaces\Model $model): void
    {
        if (!$model instanceof \CarlBennett\API\Models\Slack\Webhook)
        {
            throw new \CarlBennett\API\Exceptions\InvalidModelException($model);
        }

        echo $model->result;
        $model->_responseHeaders['Content-Type'] = self::mimeType();
    }
}
