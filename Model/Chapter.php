<?php

include_once 'Core/Annotations.php';
include_once 'Core/StorageItem.php';

/**
 * Created by PhpStorm.
 * User: clovis
 * Date: 21/03/17
 * Time: 19:48
 */
class Chapter extends StorageItem
{
    /**
     * @Numeric
     */
    public $Chapter_id;

    /**
     * @Required
     * @Word
     * @Size(min=1, max=400)
     */
    public $name;

    /**
     * @Required
     * @Word
     * @Size(min=1, max=9000)
     */
    public $content;

    /**
     * @Required
     * @Word
     * @Size(min=1,max=20)
     */
    public $ip;

    /**
     * @Word
     * @Size(min=1, max=100)
     */
    public $mail;

    /**
     * @Required
     * @Word
     * @Size(min=1, max=100)
     */
    public $username;

    /**
     * @Numeric
     */
    public $report;

    /**
     * @Required
     * @Numeric
     */
    public $public;

    /**
     * @return mixed
     */
    public function getChapterId()
    {
        return $this->Chapter_id;
    }

    /**
     * @param mixed $Chapter_id
     */
    public function setChapterId($Chapter_id)
    {
        $this->Chapter_id = $Chapter_id;
        $this->checkIntegrity("Chapter_id");

    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->checkIntegrity("name");

    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
        $this->checkIntegrity("content");

    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        $this->checkIntegrity("ip");

    }

    /**
     * @return mixed
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param mixed $mail
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
        $this->checkIntegrity("mail");

    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
        $this->checkIntegrity("username");

    }

    /**
     * @return mixed
     */
    public function getReport()
    {
        return $this->report;
    }

    /**
     * @param mixed $report
     */
    public function setReport($report)
    {
        $this->report = $report;
        $this->checkIntegrity("report");

    }

    /**
     * @return mixed
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * @param mixed $public
     */
    public function setPublic($public)
    {
        $this->public = $public;
        $this->checkIntegrity("public");
    }



}
