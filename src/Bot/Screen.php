<?php


namespace bot;


/**
 * @property array line
 */
class Screen
{
    private $lines = [];

    public function __construct($titleScreen, $lines)
    {
        $this->addLine("*$titleScreen*");
        foreach ($lines as $line) {
            $this->addLine($line);
        }
    }

    public function addLine($line) {
        array_push($this->lines, $line . "\n");
    }

    public function getText(): string {
        return implode($this->lines);
    }
}