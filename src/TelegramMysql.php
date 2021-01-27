<?php

class TelegramMysql
{
    private $mysql;

    public function __construct($config)
    {
        $this->mysql = new mysqli(
            $config['mysql']['hostname'],
            $config['mysql']['username'],
            $config['mysql']['password'],
            $config['mysql']['database_name']
        );
    }
}