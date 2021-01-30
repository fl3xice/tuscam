<?php


namespace bot;


abstract class State
{
    public function runState($fn, $input)
    {
        $fn($input);
    }
}