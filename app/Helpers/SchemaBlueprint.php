<?php
namespace App\Helpers;

use Illuminate\Database\Schema\Blueprint;

class SchemaBlueprint extends Blueprint
{
    private static $is_testing = null;

    public function __construct($table, $callback)
    {
        parent::__construct($table, $callback);
        if (self::$is_testing = (\App::environment() === 'testing')) {
            $this->engine = 'memory';
        }
    }

    public function text($column)
    {
        return self::$is_testing
            ? parent::string($column)
            : parent::text($column);
    }
}
