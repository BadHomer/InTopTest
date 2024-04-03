<?php

namespace App\View;

class View
{
    public static function get(string $fileName): false|string
    {
        echo file_get_contents(__DIR__ . "/../../resources/views/{$fileName}.php");
        return false;
    }
}