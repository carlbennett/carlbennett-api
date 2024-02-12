<?php

namespace CarlBennett\API\Models\Convert;

class IntInOut extends \CarlBennett\API\Models\Errorable implements \JsonSerializable
{
    public ?int $input = null;
    public ?int $output = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'input' => $this->input,
            'output' => $this->output,
        ]);
    }
}
