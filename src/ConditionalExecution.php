<?php


class ConditionalExecution
{

    private $execute = false;

    public function __construct($conditions = [false])
    {
        $ok = 0;
        foreach ($conditions as $condition) {
            if ($condition) {
               $ok++;
            }
        }
        if ($ok == count($conditions)) $this->execute = true;
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