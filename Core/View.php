<?php

include_once 'Core/Template.php';

/**
 * Created by PhpStorm.
 * User: clovis
 * Date: 24/01/17
 * Time: 17:32
 */
class View
{
    private $data;
    private $template;

    public static function MakeTextSafe($text)
    {
        $text = str_replace('\'', "\\'", $text);
        $text = str_replace("\"", '\\"', $text);
        //$text = str_replace("<", "&lt;", $text);
        //$text = str_replace(">", "&gt;", $text);
        //$text = str_replace(",", "&#44;", $text);
        $text = trim($text);
        return $text;
    }

    public static function MakeTextUnsafe($text)
    {
        $text = str_replace("&#39;", '\'', $text);
        $text = str_replace('&#34;',  "\"", $text);
        $text = str_replace( "&lt;", "<", $text);
        $text = str_replace( "&gt;", ">", $text);
        $text = str_replace("&#44;",","  , $text);
        $text = trim($text);
        return $text;
    }

    public static function RemoveSpecialChars($str, $charset='utf-8')
    {
        $str = htmlentities($str, ENT_NOQUOTES, $charset);

        $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
        $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères

        return $str;
    }

    function __construct($template, $data = NULL)
    {
        if($data == NULL)
            $this->data = array();
        else
            $this->data = $data;

        $this->template = $template;
    }

    /**
     * Change le titre de la vue
     * @param $title titre de la page
     */
    public function setTitle($title)
    {
        $this->setData("__title", $title);
    }

    /**
     * Change une valeur des données transmises à la vue
     * @param $key nom de la valeur
     * @param $value valeur
     */
    public function setData($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Retourne une valeur contenue dans les données transmises au template
     * @param $key clef de la donnée à récupérer
     * @return mixed valeur de la donné récupérer
     * @throws Exception L'entrée $key n'est pas fixée.
     */
    public function getData($key)
    {
        if(!isset($this->data[$key]))
            throw new Exception("The entry ".$key." is not set.");
        return $this->data[$key];
    }

    /**
     * Affiche la vue
     */
    public function show()
    {
        Template::process($this->template, $this->data);
    }
}