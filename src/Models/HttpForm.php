<?php

namespace CarlBennett\API\Models;

class HttpForm extends \CarlBennett\API\Models\Errorable implements \JsonSerializable
{
    /**
     * The key-value store of the form.
     *
     * @var array
     */
    public array $form = [];

    public function jsonSerialize(): mixed
    {
        return \array_merge(parent::jsonSerialize(), ['form' => $this->form]);
    }
}
