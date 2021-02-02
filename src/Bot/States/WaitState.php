<?php


namespace bot\States;


use bot\State;

class WaitState extends State
{
    public function run()
    {
        requestApi("sendMessage", [
            "chat_id" => $this->hook->getMessage()->getFrom()->getId(),
            "text" => "Ожидайте когда вашу заявку примут 💤",
            "parse_mode" => "markdown"
        ]);
        die();
    }
}