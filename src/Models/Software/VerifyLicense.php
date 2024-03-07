<?php

namespace CarlBennett\API\Models\Software;

class VerifyLicense extends \CarlBennett\API\Models\Errorable implements \JsonSerializable
{
    public ?array $result = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), ['result' => $this->result]);
    }
}
