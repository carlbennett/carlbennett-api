<?php

namespace CarlBennett\API\Libraries\CLI\Menus;

abstract class Menu implements \JsonSerializable
{
    public static function printMenu(string $menu_id): void
    {
        echo 'TODO: Make a ' . $menu_id . ' menu.' . \PHP_EOL;
    }
}
