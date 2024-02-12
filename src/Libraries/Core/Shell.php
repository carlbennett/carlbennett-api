<?php

namespace CarlBennett\API\Libraries\Core;

class Shell
{
    public static function execute(string $command, string $args): string|false|null
    {
        return \shell_exec($command . ' ' . \escapeshellcmd($args) . " 2>&1");
    }
}
