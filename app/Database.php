<?php

namespace APITest\Core;

class Database
{
    private $host;
    private $user;
    private $password;
    private $port;
    private $database;

    private $object;
    private $prepare;

    public function __construct()
    {
        $this->loadConfig();
        $this->connect();
    }

    private function loadConfig()
    {
        $this->host = getenv('DB_HOST');
        $this->user = getenv('DB_USERNAME');
        $this->password = getenv('DB_PASSWORD');
        $this->port = getenv('DB_PORT');
        $this->database = getenv('DB_DATABASE');
    }

    private function connect()
    {
        $this->object = new \PDO('mysql:host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->database, $this->user, $this->password);
        $this->object->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function query($sql, $arrayAttributes = [])
    {
        $prepare = $this->object->prepare($sql);

        if (!$prepare) {
            return false;
        }

        $isExecuted = $prepare->execute($arrayAttributes);

        $this->prepare = $prepare;
        return $isExecuted;
    }

    public function fetchAll($sql, $arrayAttributes = [])
    {
        $prepare = $this->object->prepare($sql);

        if (!$prepare) {
            return false;
        }

        if (!$prepare->execute($arrayAttributes)) {
            $this->prepare = $prepare;
            return false;
        }

        $data = $prepare->fetchAll(\PDO::FETCH_ASSOC);

        return $data;
    }

    public function fetchOne($sql, $arrayAttributes = [])
    {
        $prepare = $this->object->prepare($sql);

        if (!$prepare) {
            return false;
        }

        if (!$prepare->execute($arrayAttributes)) {
            $this->prepare = $prepare;
            return false;
        }

        $data = $prepare->fetch(\PDO::FETCH_ASSOC);

        $this->prepare = $prepare;

        return $data;
    }

    public function fetchRow($sql, $arrayAttributes = [])
    {
        $prepare = $this->object->prepare($sql);

        if (!$prepare) {
            return false;
        }

        if (!$prepare->execute($arrayAttributes)) {
            $this->prepare = $prepare;
            return false;
        }

        $data = $prepare->fetch(\PDO::FETCH_ASSOC);
        $this->prepare = $prepare;

        return $data;
    }
}