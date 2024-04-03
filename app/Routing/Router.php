<?php

namespace App\Routing;

use App\Controllers\CrmController;

class Router
{
    private static array $actionArray;
    public static function run(string $uri, string $method)
    {
        self::$actionArray = [
            '/' => [
                'GET' => [
                    'className' =>CrmController::class,
                    'methodName' => 'index'
                ],
                'POST' => [
                    'className' =>CrmController::class,
                    'methodName' => 'createLead'
                ]
            ],
        ];

        return self::startProcess(self::$actionArray[$uri][$method]);
    }

    private static function startProcess(array $action) {
        if(count($_POST) !== 0) {
            return (new $action['className'])->{$action['methodName']}($_POST);
        }

        return (new $action['className'])->{$action['methodName']}();
    }
}