<?php

include_once 'Core/Engine.php';

/**
 * Created by PhpStorm.
 * User: clovis
 * Date: 22/01/17
 * Time: 16:12
 */

Engine::$DEBUG = false;
date_default_timezone_set ("Europe/Paris");
Engine::Instance()->setPersistence(new DatabaseStorage("localhost", "BBCK", "root", "root"));
Engine::Instance()->run();


