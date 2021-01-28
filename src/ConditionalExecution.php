<?php


class ConditionalExecution
{

    private $execute = true;

    public function __construct($conditions = [false])
    {
        foreach ($conditions as $condition) {
            if (!$condition) {
                $this->execute = false;
            }
        }
    }

    public function Execute($fn) : bool {
        if ($this->execute) {
            $fn();
            return true;
        } else {
            return false;
        }
    }
}