<?php

namespace CarlBennett\API\Exceptions;

class SoftwareLicenseNotFoundException extends \CarlBennett\API\Exceptions\DatabaseObjectNotFoundException
{
    public function __construct(string $software_license, int $errno = 0, ?\Throwable $prev_ex = null)
    {
        parent::__construct(\sprintf('Software license not found: %s', $software_license), $errno, $prev_ex);
    }
}
