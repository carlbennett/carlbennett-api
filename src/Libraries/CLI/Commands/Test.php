<?php

namespace CarlBennett\API\Libraries\CLI\Commands;

class Test extends Command
{
    public function invoke(?CommandOptions $options = null): CommandResult
    {
        $result = new CommandResult();
        $result->setTimeStart();
        try
        {
            echo 'Executed the test command' . \PHP_EOL;
            $result->setResult(true);
        }
        finally
        {
            $result->setTimeFinish();
            return $result;
        }
    }
}
