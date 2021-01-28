<?php


namespace bot;


use bot\types\Message;

class ObjectHook
{
    private $object;

    public function __construct($object)
    {
        $this->object = $object;
    }

    public function isMessage() {
        return $this->object->message;
    }

    public function getObject()
    {
        return $this->object;
    }

    public function getMessage() : Message
    {
        return new Message($this->object->message);
    }

}