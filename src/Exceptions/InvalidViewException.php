<?php

namespace CarlBennett\API\Exceptions;

class InvalidViewException extends \LogicException
{
    public function __construct(string|\CarlBennett\API\Interfaces\View $view, int $errno = 0, ?\Throwable $prev_ex = null)
    {
        $view_name = \is_string($view) ? $view : \get_class($view);
        parent::__construct(\sprintf('View not found: %s', $view_name), $errno, $prev_ex);
    }
}
