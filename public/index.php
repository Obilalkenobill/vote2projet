<?php

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__).'/vendor/autoload.php';

(new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
if ($_SERVER['APP_DEBUG']) {
    header('Access-Control-Allow-Origin:'.rtrim($_SERVER['HTTP_REFERER'], '/'));
} else {
    header('Access-Control-Allow-Origin: https://ricbe.herokuapp.com,http://ricbe.herokuapp.com');
}
header('Access-Control-Allow-Headers:*');
header('Access-Control-Allow-Credentials:true');
header('Access-Control-Allow-Headers:*');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    die();
}


if ($_SERVER['APP_DEBUG']) {
    umask(0000);

    Debug::enable();
}

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
