<?php

namespace CarlBennett\API\Models\Slack;

class Webhook extends \CarlBennett\API\Models\Errorable implements \JsonSerializable
{
    public ?string $result = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), ['result' => $this->result]);
    }
}
