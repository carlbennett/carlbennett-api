<?php

namespace CarlBennett\API\Controllers;

abstract class Base implements \CarlBennett\API\Interfaces\Controller
{
    /**
     * The Model to be set by subclasses and used by a View.
     *
     * @var \CarlBennett\API\Interfaces\Model|null
     */
    public ?\CarlBennett\API\Interfaces\Model $model = null;
}
