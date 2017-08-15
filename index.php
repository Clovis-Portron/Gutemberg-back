<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once 'vendor/autoload.php';
include_once 'Core/Engine.php';
include_once 'Configuration.php';
include_once 'Controllers/APIController.php';

/**
 * Created by PhpStorm.
 * User: clovis
 * Date: 22/01/17
 * Time: 16:12
 */

Engine::$DEBUG = true;
date_default_timezone_set ("Europe/Paris");
Engine::Instance()->setPersistence(new DatabaseStorage(Configuration::$DB_hostname, Configuration::$DB_name, Configuration::$DB_username, Configuration::$DB_password));

$config = [
    'settings' => [
        'displayErrorDetails' => true,
    ],
];
$app = new \Slim\App($config);
$app->any('/api/addchapter', function (Request $request, Response $response) {
    APIController::GetInstance()->AddChapter();
    return $response;
});
$app->any('/api/getchapter', function (Request $request, Response $response) {
    APIController::GetInstance()->Get("Chapter");
    return $response;
});
$app->any('/api/getchapters', function (Request $request, Response $response) {
    APIController::GetInstance()->GetAll("Chapter");
    return $response;
});
$app->run();

