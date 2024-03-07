<?php

namespace CarlBennett\API\Libraries\CLI\Commands;

class CommandResult implements \JsonSerializable
{
    private mixed $raw_result = null;
    private float $time_start = 0.0;
    private float $time_finish = 0.0;

    public function __construct(mixed $raw_result = null)
    {
        $this->setResult($raw_result);
    }

    public function jsonSerialize(): mixed
    {
        return [
            $this->time_finish - $this->time_start,
            $this->raw_result,
        ];
    }

    public function getResult(): mixed
    {
        return $this->raw_result;
    }

    public function getTimeFinish(): float
    {
        return $this->time_finish;
    }

    public function getTimeStart(): float
    {
        return $this->time_start;
    }

    public function setResult(mixed $value): void
    {
        $this->raw_result = $value;
    }

    public function setTimeFinish(?float $value = null): void
    {
        $this->time_finish = $value ?? \microtime(true);
    }

    public function setTimeStart(?float $value = null): void
    {
        $this->time_start = $value ?? \microtime(true);
    }
}
