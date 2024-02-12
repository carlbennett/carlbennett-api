<?php

namespace CarlBennett\API\Libraries\Core;

class DateTimeImmutable extends \DateTimeImmutable implements \JsonSerializable
{
    public function jsonSerialize(): mixed
    {
        return [
            'iso' => $this->format(\DateTimeInterface::RFC2822),
            'tz' => $this->format('e'),
            'unix' => (int) $this->format('U'),
        ];
    }

    public function __toString(): string
    {
        try
        {
            \ob_start();

            foreach ($this->jsonSerialize() as $key => $value)
            {
                \printf('%s %s%s', $key, $value, \PHP_EOL);
            }
        }
        finally
        {
            return \ob_get_clean();
        }
    }
}
