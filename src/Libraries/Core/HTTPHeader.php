<?php

namespace CarlBennett\API\Libraries\Core;

class HTTPHeader extends \CarlBennett\API\Libraries\Core\KeyValuePair
{
    public function __toString(): string
    {
        return \sprintf('%s: %s', $this->getKey(), $this->getValue());
    }
}
