<?php

require_once 'vendor/autoload.php';
include_once 'addendum/annotations.php';
include_once 'Annotations.php';


include_once 'DatabaseStorage.php';
session_start();

/**
 * Created by PhpStorm.
 * User: clovis
 * Date: 22/01/17
 * Time: 16:02
 */

class Engine
{

    private static $instance;
    public static $DEBUG = false;

    public static function Instance()
    {
        if(isset(Engine::$instance) ==  false)
        {
            Engine::$instance = new Engine();
        }
        return Engine::$instance;
    }



    public static function autoload($class)
    {
        if(file_exists('Controllers/'.$class.'.php'))
            include_once 'Controllers/'.$class.'.php';
        else if(file_exists('Model/'.$class.'.php'))
            include_once 'Model/'.$class.'.php';
    }

    private $persistence;

    function __construct()
    {
        $this->persistence = array();
        spl_autoload_register('Engine::autoload');
    }

    public function setPersistence($storage)
    {
        $key = get_class($storage);
        if(isset($this->persistence[$key]))
            throw new Exception("you cant change persistence if already set.");
        if(!is_subclass_of($storage, "Storage"))
            throw new Exception("Must be a child class of Storage");
        $this->persistence[$key] = $storage;
    }
    public function Persistence($class)
    {
        if(!isset($this->persistence[$class]))
            throw new Exception($class." not registered as Persistent Storage");
        return $this->persistence[$class];
    }

    public function run()
    {
        if(Engine::$DEBUG)
        {
            foreach (array_keys($_GET) as $key)
            {
                $_POST[$key] = $_GET[$key];
            }
        }

        $ctx = explode(",", $_GET["args"]);
        $matches = array();

        $class = NULL;
        if(isset($_GET["p"]) == false || strlen($_GET["p"]) <= 0 || $_GET["p"] == "Test.php")
            $class = "Default";
        else
            $class = $_GET["p"];

        $uri = "Controllers/".$class."Controller.php";

        if(file_exists($uri) == false) {
            header("Location: Error/404");
            return;
        }

        $class = $class."Controller";

        preg_match_all("/([^\/]+)/", $_SERVER["REQUEST_URI"], $matches);

        $controller = new $class($matches[0]);
        $controller->run($ctx);
    }
}
