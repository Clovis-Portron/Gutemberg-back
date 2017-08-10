<?php

/**
 * Created by PhpStorm.
 * User: clovis
 * Date: 01/07/17
 * Time: 12:52
 */
class API
{
    /**
     * @param $token
     * @return User
     * @throws Exception
     */
    public static function Auth($token)
    {
        /*$storage = Engine::Instance()->Persistence("DatabaseStorage");
        $users = null;
        $storage->findAll("User", $users);
        foreach ($users as $user)
        {
            if($user->checkAuth($token)) {
                return $user;
            }
        }
        throw new Exception("Invalid Token", 0);*/
    }

    public static function CheckRights($token, $level)
    {
        /*$user = API::Auth($token);
        if($user->Rights() < $level)
            throw new Exception("Not Enough Power", 1);*/
    }

    public static function Add($token, $item, $check = true)
    {
        if($item == null || $item->Id() != null)
            throw new InvalidArgumentException();
        if($check)
            API::CheckRights($token, 1);

        $storage = Engine::Instance()->Persistence("DatabaseStorage");
        $item->setStorage($storage);
        $storage->persist($item);
        $storage->flush();
        return $item->Id();
    }

    public static function Remove($token,$class, $id, $check = true)
    {
        if($check)
            API::CheckRights($token, 1);

        $storage = Engine::Instance()->Persistence("DatabaseStorage");
        $entry = new $class($storage, $id);
        $storage->remove($entry);
        $storage->flush();
    }
    
    public static function Update($token, $item, $check = true)
    {
        if($item == null || $item->Id() == null)
            throw new InvalidArgumentException();
        if($check)
            API::CheckRights($token, 1);

        $storage = Engine::Instance()->Persistence("DatabaseStorage");
        $item->setStorage($storage);
        $storage->persist($item, StorageState::ToUpdate);
        $storage->flush();
    }

    public static function GetAll($class, $filters = null)
    {
        $storage = Engine::Instance()->Persistence("DatabaseStorage");
        $items = null;
        $f = "";
        if($filters != null) {
            $filters=str_replace("\\","", $filters);
            $filters = json_decode($filters);
            foreach($filters as $key => $value)
            {
                if(is_array($value))
                {
                    for($i = 0; $i < count($value); $i++)
                    {
                        if(is_numeric($value[$i]))
                        {
                            $f .= $key." = '".$value[$i]."' OR ";
                        }
                        else
                        {
                            $f .= $key." LIKE '%".$value[$i]."%' OR ";
                        }
                    }
                    $f = substr($f,0, -3)."AND ";
                }
                else
                {
                    if(is_numeric($value))
                    {
                        $f .= $key." = '".$value."' AND ";
                    }
                    else
                    {
                        $f .= $key." LIKE '%".$value."%' AND ";
                    }
                }
            }
            $f = substr($f,0, -4);
        }
        $storage->findAll($class, $items, $f);
        return $items;
    }

    public static function Get($class, $id)
    {
        //API::CheckRights($token, 1);
        if($id == null)
            return null;
        $storage = Engine::Instance()->Persistence("DatabaseStorage");
        $item = new $class($storage, $id);
        $item = $storage->find($item);
        if($item == null)
            return null;
        $result = get_object_vars($item);
        /*foreach($item as $key => $value)
        {
            /*if(strpos($key, "_id") !== false)
            {
                $nk = str_replace("_id", "", $key);
                $result[strtolower($nk)] = API::Get($token,$nk, $value);
            }
            if($key == "password") // On masque le champs password
                $result[$key] = null;
        }*/
        return $result;
    }




}
