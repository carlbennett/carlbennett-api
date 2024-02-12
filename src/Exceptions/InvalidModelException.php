<?php

namespace CarlBennett\API\Exceptions;

class InvalidModelException extends \LogicException
{
    public function __construct(string|\CarlBennett\API\Interfaces\Model $model, int $errno = 0, ?\Throwable $prev_ex = null)
    {
        $model_name = \is_string($model) ? $model : \get_class($model);
        parent::__construct(\sprintf('Model not found: %s', $model_name), $errno, $prev_ex);
    }
}
