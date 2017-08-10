<?php

/**
 * Created by PhpStorm.
 * User: clovis
 * Date: 20/01/17
 * Time: 19:29
 */
class Template
{

    /**
     * Execute et affiche un template
     * @param $name Nom du template
     * @param $data Données à insérer
     */
    public static function process($name, $data)
    {
        $content = Template::open($name.".html");
        echo Template::prepare($content, $data);
    }

    /**
     * Lit un fichier de template $file et retourne son contenu
     * @param string $file fichier de template à lire
     * @return string Contenu du fichier
     */
    public static function open($file)
    {

        if(file_exists("Templates/".$file) == false)
            throw new Exception("Unable to find the template ".$file.".");
        $content = file_get_contents("Templates/".$file);
        return $content;
    }

    /**
     * Lit le contenu d'un template et produit du code html en fonction de $data
     * @param string $content contenu du template de base
     * @param array $data Dictionnaire de données à insérer dans le template
     * @param bool $fatal si vrai, arrete lors de la rencontre d'une erreur fatale
     * @return mixed contenu du template modifié
     * @throws Exception
     */
    public static function prepare($content, $data, $fatal = true)
    {

        $matches = array();

        // recherche et concaténation des includes
        preg_match_all("/{{@(.*?)}}/", $content, $matches);
        foreach($matches[1] as $match)
        {
            $key = str_replace(" ", "", $match).".html";
            $inner_content = Template::open($key);
            $inner_content = Template::prepare($inner_content, $data);
            $content = preg_replace("/{{@".$match."}}/", $inner_content, $content, 1);
        }

        // recherche et expansion des boucles

        $reg = "/{{#(.*?)}}\s*?((.|\s)*?)\s*?{{\/\g1}}/";
        preg_match_all($reg, $content, $matches);
        for($i = 0; $i!= count($matches[1]); $i++)
        {
            $array_name = str_replace(" ", "", $matches[1][$i]);

            if(isset($data[$array_name]) == false)
            {
                throw new Exception("'".$array_name."' not specified in data.");
            }
            if(is_array($data[$array_name]) == false)
                throw new Exception($array_name." is not an array.");
            $body = "";

            for($u = 0; $u != count($data[$array_name]); $u++)
            {
                $body = $body.$matches[2][$i];
                $body = Template::prepare($body, $data[$array_name][$u], false);
            }
            $content = preg_replace("/{{#(.*?)}}\s*?((.|\s)*?)\s*?{{\/\g1}}/", $body, $content, 1);

        }

        // recherche et remplacement des conditions
        preg_match_all("/{{=(.*?)}}\s*?((.|\s)*?)\s*?{{\/\g1}}/", $content, $matches);
        for($i = 0; $i!= count($matches[1]); $i++)
        {
            $match = $matches[1][$i];

            $key = str_replace(" ", "", $match);
            if(isset($data[$key]) == false && $fatal == true)
            {
                throw new Exception($key." not specified in data.");
            }
            else if(isset($data[$key]) == false && $fatal == false)
                continue;
            else if(is_bool($data[$key]) == false)
                continue;
            if($data[$key] == false)
            {
                $content = preg_replace("/{{=(.*?)}}\s*?((.|\s)*?)\s*?{{\/\g1}}/", "", $content, 1);
                continue;
            }
            $body = $matches[2][$i];
            $body = Template::prepare($body, $data);
            $content = preg_replace("/{{=(.*?)}}\s*?((.|\s)*?)\s*?{{\/\g1}}/", $body, $content, 1);
        }



        // recherche et remplacement des données
        //recherche des variables
        preg_match_all("/{{([^=\/#]*?)}}/", $content, $matches);
        foreach($matches[1] as $match)
        {
            $key = str_replace(" ", "", $match);
            if(isset($data[$key]) == false && $fatal == true)
            {
                header("Content-type: text/plain");
                echo $content.'<br><br>';
                throw new Exception($key." not specified in data.");
            }
            else if(isset($data[$key]) == false && $fatal == false)
                continue;
            $content = preg_replace("/{{".$match."}}/", $data[$key], $content, 1);
        }

        return $content;
    }
}