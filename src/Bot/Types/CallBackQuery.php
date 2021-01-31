<?php


namespace bot\Types;


class CallBackQuery
{
    public $id;
    private $from;
    public $inline_message_id;
    public $chat_instance;
    public $data;
    public $game_short;

    public function __construct($callback)
    {
        $this->id = $callback->id;
        $this->from = $callback->from;
        $this->inline_message_id = $callback->inline_message_id;
        $this->chat_instance = $callback->chat_instance;
        $this->data = $callback->data;
        $this->game_short = $callback->game_short;
    }

    /**
     * @return User
     */
    public function getFrom() : User
    {
        return new User($this->from);
    }
}