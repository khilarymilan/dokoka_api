<?php

namespace App\Exceptions;

use Exception;

class AppException extends Exception
{
    public static function messages()
    {
        return [];
    }

    public static function throws($code)
    {
        throw new static(static::message($code));
    }

    public static function message($code)
    {
        return __(static::messages()[$code]);
    }
}
