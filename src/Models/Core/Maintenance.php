<?php

namespace CarlBennett\API\Models\Core;

class Maintenance extends \CarlBennett\API\Models\Errorable implements \JsonSerializable
{
    public ?string $message = null;

    public function jsonSerializable(): mixed
    {
        return \array_merge(parent::jsonSerialize(), ['message' => $this->message]);
    }
}
