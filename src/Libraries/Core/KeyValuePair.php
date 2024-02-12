<?php

namespace CarlBennett\API\Libraries\Core;

use \OutOfBoundsException;

class KeyValuePair implements \ArrayAccess, \JsonSerializable
{
    public const OFFSET_KEY = 'key';
    public const OFFSET_VALUE = 'value';

    private mixed $key = null;
    private mixed $value = null;

    public function __construct(mixed $key, mixed $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public function getKey(): mixed
    {
        return $this->key;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function jsonSerialize(): mixed
    {
        return [
            self::OFFSET_KEY => $this->getKey(),
            self::OFFSET_VALUE => $this->getValue(),
        ];
    }

    public function offsetExists(mixed $offset): bool
    {
        return $offset === 0 || $offset === self::OFFSET_KEY
            || $offset === 1 || $offset === self::OFFSET_VALUE;
    }

    public function offsetGet(mixed $offset): mixed
    {
        switch ($offset)
        {
            case 0:
            case self::OFFSET_KEY:
                return $this->key;
            case 1:
            case self::OFFSET_VALUE:
                return $this->value;
            default:
                throw new OutOfBoundsException();
        }
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        switch ($offset)
        {
            case 0:
            case self::OFFSET_KEY:
                $this->key = $value; break;
            case 1:
            case self::OFFSET_VALUE:
                $this->value = $value; break;
            default:
                throw new OutOfBoundsException();
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        switch ($offset)
        {
            case 0:
            case self::OFFSET_KEY:
                $this->key = null;
            case 1:
            case self::OFFSET_VALUE:
                $this->value = null;
            default:
                throw new OutOfBoundsException();
        }
    }

    public function setKey(mixed $key): void
    {
        $this->key = $key;
    }

    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }
}
