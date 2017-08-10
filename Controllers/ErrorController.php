<?php

include_once 'Core/Controller.php';
include_once 'Utils/OIMView.php';
include_once 'Utils/Api.php';

/**
 * Created by PhpStorm.
 * User: clovis
 * Date: 22/01/17
 * Time: 16:41
 */
class ErrorController extends Controller
{
    private $code;

    function __construct($params)
    {
        parent::__construct($params);
        $this->code = $params[1];
    }

    public function run($ctx)
    {
        if(Api::isLogged() == false)
        {
            header("location: /Login");
            return;
        }
        $data["code"] = $this->code;

        $data["rw"] = Api::isRW();
        $view = new OIMView("Error/base", $data);
        $view->setTitle("Erreur ".$this->code);
        $view->show();
    }
}