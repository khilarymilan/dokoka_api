<?php

namespace App\Repositories;

use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class Repository
 * @package App\Repositories
 *
 * @property Collection $collection
 * @property string $model
 */
abstract class CollectionRepository extends Collection
{
    protected $model;
    private static $pdo = null;

    public function __construct($src = null)
    {
        $this->makeCollection($src);
    }

    /**
     * Gets PDO instance
     *
     * @return null|\PDO
     */
    private static function pdo()
    {
        return self::$pdo ?: (self::$pdo = DB::connection()->getPdo());
    }

    /**
     * Escapes a string to prevent SQL injection
     *
     * @param $str
     * @return string
     */
    public static function escape($str)
    {
        return self::pdo()->quote($str);
    }

    /**
     * @param $src
     * @return null
     */
    private function getCollection($src)
    {
        switch (true) {
            case $src === null:
                return new Collection;

            case is_object($src) &&
                is_a($src, static::class):
                return $src->items;

            case is_object($src) &&
                get_class($src) === Collection::class:
                return $src;

            case is_object($src) &&
                get_class($src) === $this->model:
                return new Collection($src);

            case is_object($src) &&
                get_class($src) === Builder::class &&
                get_class($src->getModel()) === $this->model:
                return $src->get();

            case is_array($src):
                return ($this->model)::where($src)->get();
        }

        return null;
    }


    /**
     * Create an instance of the repository model
     *
     * @param null|int|\Model|CollectionRepository|Collection $src
     */
    private function makeCollection($src = null)
    {
        $model = $this->model;

        if ($this->model === null) {
            $model = explode('\\', get_class($this));
            $model = 'App\\Models\\' . preg_replace('/CollectionRepository$/', '', array_pop($model));
        }

        $this->model = $model;
        $this->items = $this->getCollection($src) ?: [];
    }

    /**
     * Update all items in collection
     *
     * @param $key_values
     */
    public function update($key_values)
    {
        $this->each(
            function ($item) use ($key_values) {
                $item->update($key_values);
            }
        );
    }

    /** Delete all items in collection */
    public function delete()
    {
        $this->each(
            function ($item) {
                $item->delete();
            }
        );
        $this->items = [];
    }

    public static function prepare($qry, array $arr_bindings = [])
    {
        return Repository::prepare($qry, $arr_bindings);
    }

    public static function sql($file, array $arr_bindings = [])
    {
        return Repository::sql($file, $arr_bindings);
    }

    public static function exec($qry)
    {
        Repository::exec($qry);
    }

    public static function execPrepared($qry, array $arr_bindings = [])
    {
        Repository::execPrepared($qry, $arr_bindings);
    }

    public static function execSql($sqlfile, array $arr_bindings = [])
    {
        Repository::execSql($sqlfile, $arr_bindings);
    }

    public static function selectSql($sqlfile, array $arr_bindings = [])
    {
        return Repository::selectSql($sqlfile, $arr_bindings);
    }
}
