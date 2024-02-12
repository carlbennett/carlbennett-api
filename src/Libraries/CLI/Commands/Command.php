<?php

namespace CarlBennett\API\Libraries\CLI\Commands;

abstract class Command
{
    public abstract function invoke(?CommandOptions $options = null): CommandResult;

    public static function invokeFromString(string $command, ?CommandOptions $options = null): ?CommandResult
    {
        switch (\strtolower(\trim($command)))
        {
            case 'test': return (new \CarlBennett\API\Libraries\CLI\Commands\Test())->invoke($options);
            default:
            {
                \file_put_contents('php://stderr', \sprintf('Command not found: %s%s', $command, \PHP_EOL));
                return null;
            }
        }
    }
}
