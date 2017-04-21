<?php

namespace App\Helpers;

class Str
{
    private $str;

    protected function __construct($str)
    {
        $this->str = $str;
    }

    public function __toString()
    {
        return (string)$this->str;
    }

    public static function __callStatic($name, $args)
    {
        $str = (string)array_shift($args);
        $fn = new \ReflectionMethod(self::class, $name);
        $fn->setAccessible(true);
        return $fn->invokeArgs(new self($str), $args);
    }

    protected function lowerSnake()
    {
        return new self(preg_replace('/[^_a-z0-9]+/i', '_', strtolower($this->str)));
    }
}
