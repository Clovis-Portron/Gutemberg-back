<?php

include_once "Core/Controller.php";
include_once 'Applicative/API.php';

/**
 * Created by PhpStorm.
 * User: clovis
 * Date: 22/01/17
 * Time: 16:15
 */
class APIController extends Controller
{

    public static $OK = "OK";
    public static $NO = "NO";

    public function run($ctx)
    {
	    header("Access-Control-Allow-Origin: *");
        $ope = strtolower($ctx[0]);
        foreach ($_POST as $key => $value)
        {
            if($value == "null") {
                $_POST[$key] = null;
                continue;
            }else {
                $_POST[$key] = trim($_POST[$key]);
                $_POST[$key] = View::MakeTextSafe($value);
            }
        }

        try {
            switch ($ope) {
                case "addchapter":
                    $this->AddChapter();
                    break;
                case "getchapter":
                    $this->Get("Chapter");
                    break;
                case "getchapters":
                    $this->GetAll("Chapter");
                    break;
                default:
                    http_response_code(404);
                    return;

            }
        } catch (Exception $e) {
            $this->Write(APIController::$NO, $e->getCode(), $e->getMessage() . "\n\n" . $e->getTraceAsString());
            return;
        }
    }

    private function Auth()
    {
        if(isset($_POST["token"]) == false)
        {
            $this->Write(APIController::$NO, null, "Missing Token");
            return;
        }
        $user = API::Auth($_POST["token"]);
        $user = get_object_vars($user);
        $user["password"] = null;
        $this->Write(APIController::$OK, $user);
    }

    private function Get($class)
    {
        if(isset($_POST["id"]) == false)
        {
            $this->Write(APIController::$NO, null, "Missing Data");
            return;
        }
        $method = "Get".$class;
        $res = null;
        $token = null;
        if(isset($_POST["token"]))
            $token = $_POST["token"];
        if(method_exists("API", $method) == false)
            $res = API::Get($token, $class, $_POST["id"]);
        else
            $res = API::$method($token, $_POST["id"]);
        $this->Write(APIController::$OK, $res);
    }

    private function GetAll($class)
    {
        $filters = null;
        /*if(isset($_POST["token"]) == false)
        {
            $this->Write(APIController::$NO, null, "Missing Token");
            return;
        }*/
        if(isset($_POST["filters"]))
        {
            $filters = $_POST["filters"];
        }
        $method = "GetAll".$class;
        $res = null;
        if(method_exists("API", $method) == false)
            $res = API::GetAll(null, $class, $filters);
        else
            $res = API::$method(null, $filters);
        $this->Write(APIController::$OK, $res);
    }

    private function Add($item)
    {
        if (isset($_POST["token"]) == false) {
            $this->Write(APIController::$NO, null, "Missing Token");
            return;
        }
        $func = "Add" . get_class($item);
        $id = null;
        if(method_exists("API", $func) == false)
            $id = API::Add($_POST["token"], $item);
        else
            $id = Api::$func($_POST["token"], $item);
        $this->Write(APIController::$OK, $id);
    }

    private function Remove($class)
    {
        if (isset($_POST["token"]) == false || isset($_POST["id"]) == false) {
            $this->Write(APIController::$NO, null, "Missing Data");
            return;
        }
        $func = "Remove" . $class;
        if(method_exists("API", $func) == false)
            API::Remove($_POST["token"],$class,  $_POST["id"]);
        else
            Api::$func($_POST["token"], $_POST["id"]);
        $this->Write(APIController::$OK, null);
    }

    private function Update($item)
    {
        if (isset($_POST["token"]) == false) {
            $this->Write(APIController::$NO, null, "Missing Token");
            return;
        }
        $func = "Update" . get_class($item);
        API::$func($_POST["token"], $item);
        if(method_exists("API", $func) == false)
            API::Update($_POST["token"], $item);
        else
            Api::$func($_POST["token"], $item);
        $this->Write(APIController::$OK, null);
    }

    private function Write($state, $data, $message = "")
    {
	    header("Access-Control-Allow-Origin: *");
        header('Content-Type: application/json');
        $result = array();
        $result["state"] = $state;
        $result["message"] = $message;
        $result["data"] = $data;
        print json_encode($result);
    }

    private function AddChapter()
    {

        if(isset($_POST["name"]) == false
        || isset($_POST["content"]) == false
        || isset($_POST["username"]) == false
        || isset($_POST["public"]) == false)
        {
            $this->Write(APIController::$NO, null, "Missing Data");
            return;
        }

        $chapter = new Chapter(null);
        $chapter->setName($_POST["name"]);
        $chapter->setContent($_POST["content"]);
        $chapter->setUsername($_POST["username"]);
        $chapter->setReport(0);
        $chapter->setPublic($_POST["public"]);

        if(isset($_POST["mail"]))
            $chapter->setMail($_POST["mail"]);
        if(isset($_POST["Chapter_id"]))
            $chapter->setChapterId($_POST["Chapter_id"]);

        $ip = getenv('HTTP_CLIENT_IP')?:
            getenv('HTTP_X_FORWARDED_FOR')?:
                getenv('HTTP_X_FORWARDED')?:
                    getenv('HTTP_FORWARDED_FOR')?:
                        getenv('HTTP_FORWARDED')?:
                            getenv('REMOTE_ADDR');

        $chapter->setIp($ip);
        $id = API::Add(null, $chapter);
        $this->Write(APIController::$OK, $id);
    }



}
