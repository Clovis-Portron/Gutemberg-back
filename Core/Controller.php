<?php

/**
 * Created by PhpStorm.
 * User: clovis
 * Date: 22/01/17
 * Time: 16:11
 */

include_once 'Core/View.php';

abstract class Controller
{

    function __construct($params)
    {
    }

    abstract public function run($ctx);
}