<?php

namespace CarlBennett\API\Libraries\CLI;

use \CarlBennett\API\Libraries\CLI\Commands\Command;
use \CarlBennett\API\Libraries\CLI\Commands\CommandResult;

class Handler
{
    public const INVOKE_SUCCESS = 0;
    public const INVOKE_FAILED = 1;

    public static function invoke(int $argc, array $argv): int
    {
        if ($argc != \count($argv))
        {
            throw new \InvalidArgumentException('Inconsistent argument count: argc != count(argv)');
        }

        $commands = [];
        $options = new \CarlBennett\API\Libraries\CLI\Commands\CommandOptions();
        $unhandled = 0;

        for ($i = 1; $i < $argc; $i++)
        {
            $arg = $argv[$i];
            if (empty($arg)) continue;

            $switch = false;
            if (\strlen($arg) > 1 && $arg[0] == '-' && $arg[1] == '-')
            {
                $switch = $arg[0] . $arg[1];
                $arg = \substr($arg, 2);
            }
            else if ($arg[0] == '-' || $arg[0] == '/')
            {
                $switch = $arg[0];
                $arg = \substr($arg, 1);
            }

            if ($switch)
            {
                $value = null;
                $delimiter = \strpos($arg, '=');
                if ($delimiter !== false)
                {
                    $value = \substr($arg, $delimiter + 1);
                    $arg = \substr($arg, 0, $delimiter);
                }

                switch ($arg)
                {
                    case 'v':
                    case 'verbose':
                    {
                        $options['verbose'] = \is_null($value) || (\is_string($value) && ((bool) $value));
                        printf('Setting verbosity to %s.%s', $options['verbose'] ? 'ON' : 'OFF', \PHP_EOL);
                        break;
                    }
                    default:
                    {
                        $unhandled++;
                        if (\is_null($value))
                        {
                            \file_put_contents('php://stderr', \sprintf('Unknown argument: %s%s%s', $switch, $arg, \PHP_EOL));
                        }
                        else
                        {
                            \file_put_contents('php://stderr', \sprintf('Unknown argument: %s%s=%s%s', $switch, $arg, $value, \PHP_EOL));
                        }
                    }
                }
            }
            else
            {
                $commands[] = $arg;
            }
        }

        if ($unhandled > 0 || \count($commands) == 0)
        {
            \CarlBennett\API\Libraries\CLI\Menus\Menu::printMenu('help');
            return self::INVOKE_FAILED;
        }
        else
        {
            foreach ($commands as $arg)
            {
                $result = Command::invokeFromString($arg, $options);

                if ($result instanceof CommandResult)
                {
                    \file_put_contents('php://stderr', \sprintf(
                        '{Command took %0.f seconds}%s', $result->getTimeFinish() - $result->getTimeStart(), \PHP_EOL
                    ));
                }

                if (!$result || ($result instanceof CommandResult && !$result->getResult()))
                {
                    return self::INVOKE_FAILED;
                }
            }
        }

        return self::INVOKE_SUCCESS;
    }
}
