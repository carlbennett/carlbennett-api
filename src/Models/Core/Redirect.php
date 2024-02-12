<?php

namespace CarlBennett\API\Models\Core;

class Redirect extends \CarlBennett\API\Models\Errorable implements \JsonSerializable
{
    public string $location = '';

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), ['location' => $this->location]);
    }
}
