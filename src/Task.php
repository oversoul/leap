<?php
namespace Aecodes\Leap;

class Task
{
    protected $line;
    protected $number;

    public function __construct(string $line, int $number)
    {
        $this->line = $line;
        $this->number = $number;
    }

    public function getLine()
    {
        return trim($this->line);
    }

    public function getLineNumber()
    {
        return $this->number;
    }

    public function renderLine($size = 12, $word = 'line')
    {
        $string = "{$word} {$this->number}";
        $spaces = str_repeat(' ', $size - strlen($string));

        return $string . $spaces;
    }
}
