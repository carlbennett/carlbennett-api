<?php

namespace CarlBennett\API\Libraries\CLI\Commands;

class CommandOptions implements \ArrayAccess, \JsonSerializable
{
    private array $options = [];

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function jsonSerialize(): mixed
    {
        return $this->options;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->options[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->options[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->options[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->options[$offset]);
    }
}
