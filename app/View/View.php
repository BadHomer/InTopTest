<?php

namespace App\View;

class View
{
    public static function get(string $fileName): void
    {
        echo file_get_contents(__DIR__ . "/../../resources/views/{$fileName}.php");

    }
}