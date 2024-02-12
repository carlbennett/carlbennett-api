<?php

namespace CarlBennett\API\Models\Convert;

class StringInOut extends \CarlBennett\API\Models\Errorable implements \JsonSerializable
{
    public ?string $input = null;
    public ?string $output = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'input' => $this->input,
            'output' => $this->output,
        ]);
    }
}
