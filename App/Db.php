<?php

class Db
{
    public $db;
    public function __construct()
    {
        $config = require 'config.php';
        $this->db = new PDO('mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'], $config['login'], $config['password']);
    }
}
?>