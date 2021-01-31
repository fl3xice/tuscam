<?php


namespace bot;


abstract class State
{
    public $mysqli;
    public $hook;

    public function __construct(\TelegramMysql $mysqli, ObjectHook $hook)
    {
        $this->mysqli = $mysqli;
        $this->hook = $hook;
    }

    public function run() {}
    public function nextStage($nowStage) {
        $this->mysqli->setInput($this->hook->getMessage()->getFrom(), $nowStage+1);
    }
}