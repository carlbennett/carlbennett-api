<?php

namespace CarlBennett\API\Models\Convert;

class MixedInOut extends \CarlBennett\API\Models\Errorable implements \JsonSerializable
{
    public mixed $input = null;
    public mixed $output = null;

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), [
            'input' => $this->input,
            'output' => $this->output,
        ]);
    }
}
