<?php


use App\Routing\Router;
use App\Services\AmoServices\AmoAuthService;
use Symfony\Component\Dotenv\Dotenv;

ini_set('display_errors', 1);
error_reporting(E_ERROR);

require __DIR__.'/../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/../.env');

[$uri, $args] = explode('?', $_SERVER['REQUEST_URI']);

$method = $_SERVER['REQUEST_METHOD'];

Router::run($uri, $method);