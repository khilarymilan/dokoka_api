<?php

namespace App\Repositories;

use App;
use DB;
use Illuminate\Database\Query\Expression;
use Model;

/**
 * Class Repository
 * @package App\Repositories
 *
 * @property Model $model
 */
abstract class Repository
{
    protected $model;
    private static $pdo = null;

    public function __construct($src = null)
    {
        $this->makeModel($src);
    }

    /**
     * Gets PDO instance
     *
     * @return null|\PDO
     */
    public static function pdo()
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
        switch (true) {
            case is_object($str) && is_a($str, Expression::class):
                return $str;
        }

        return self::pdo()->quote($str);
    }

    /**
     * Gets current date time (Y-m-d H:i:s)
     *
     * @return string
     */
    public static function datetime()
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * Gets the table name of model
     *
     * @return mixed
     */
    public static function getTable()
    {
        return (new static)->model->getTable();
    }

    /**
     * Triggered before creating / updating
     *
     * @param $model
     */
    public function saving($model)
    {
    }

    /**
     * Triggered after row has been created / updated
     *
     * @param $model
     */
    public function saved($model)
    {
    }

    /**
     * Triggered before creating
     *
     * @param $model
     */
    public function creating($model)
    {
    }

    /**
     * Triggered after row has been created
     *
     * @param $model
     */
    public function created($model)
    {
    }

    /**
     * Triggered before updating
     *
     * @param $model
     */
    public function updating($model)
    {
    }

    /**
     * Triggered after row has been updated
     *
     * @param $model
     */
    public function updated($model)
    {
    }

    /**
     * @param $src
     * @return null
     */
    public function getModel($src)
    {
        switch (true) {
            // if source is a Repository instance, grab the model attribute of the instance
            case is_object($src) && is_a($src, static::class):
                return $src->model;

            // if source is a Model instance, set the model as an attribute
            case is_object($src) && get_class($src) === get_class($this->model):
                return $src;
                break;

            // if source is an numerical ID, find the model with the ID
            case is_numeric($src):
                return $this->model->find($src);
                break;

            // if source is an array, search the table with a satisfying array conditions
            case is_array($src):
                return $this->model->where($src)->first();
                break;
        }

        return null;
    }


    /**
     * Create an instance of the repository model
     */
    private function makeModel($src = null)
    {
        $model = $this->model;

        if (empty($this->model)) {
            $model = explode('\\', get_class($this));
            $model = 'App\\Models\\' . preg_replace('/Repository$/', '', array_pop($model));
        }

        $this->model = App::getInstance()->make($model);
        $this->model = $this->getModel($src) ?: $this->model;
    }

    /**
     * Enable / disable foreign key checks
     *
     * @param bool $bool
     */
    public static function foreignChecks($bool = true)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=' . (int)(!!$bool));
    }

    /**
     * Empty table (destructive). Use only for seeding / testing.
     */
    public function clear()
    {
        self::foreignChecks(false);
        $this->model->truncate();
        self::foreignChecks(true);
    }

    /**
     * Query builder
     *
     * @param array $cols
     * @return mixed
     */
    public function select(array $cols)
    {
        return $this->model->select($cols);
    }

    /**
     * @param array $columns
     * @return mixed
     */
    public function all($columns = array('*'))
    {
        return $this->model->get($columns);
    }

    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 15, $columns = array('*'))
    {
        return $this->model->paginate($perPage, $columns);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function update(array $data, $id = null)
    {
        $model = $this->model;
        if ($id !== null) {
            $model = $this->model->find($id);
        }
        return $model->fill($data)->save();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id = null)
    {
        $model = $this->model;
        if ($id !== null) {
            $model = $this->model->find($id);
        }
        return $model->delete();
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*'))
    {
        return $this->model->find($id, $columns);
    }

    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy($attribute, $value, $columns = array('*'))
    {
        return $this->model->where($attribute, '=', $value)->first($columns);
    }

    /**
     * Updates multiple rows
     *
     * @param array $rows
     * @param array $pk
     * @return bool
     */
    public function updateMultiple(array $rows, $pk = ['id'])
    {
        if (empty($pk)) {
            $pk = 'id';
        }
        if (!is_array($pk)) {
            $pk = [$pk];
        }
        $sql = '';
        foreach ($rows as $row) {
            $sql .=
                "UPDATE `" . self::getTable() . "` SET " .
                join(',', array_map(function ($key) use ($row) {
                    return "`{$key}`=" . self::escape($row[$key]);
                }, array_keys($row))) .
                " WHERE " .
                join(' AND ', array_map(function ($key) use ($row) {
                    return "`{$key}`=" . self::escape($row[$key]);
                }, $pk)) . ";\n";
        }
        return DB::unprepared($sql);
    }

    /**
     * Inserts or updates multiple rows
     *
     * @param $insertRows
     * @param $updateRows
     */
    public function insertUpdateMultiple(array $insertRows, array $updateRows = [])
    {
        $sql = '';
        foreach ($insertRows as $i => $insertRow) {
            $updateRow = @$updateRows[$i];
            $sql .=
                "INSERT " . (empty($updateRow) ? 'IGNORE' : '') .
                " INTO `" . $this->getTable() . "`(`" . implode('`,`', array_keys($insertRow)) . "`)" .
                " VALUES (" .
                join(',', array_map(function ($s) {
                    return self::escape($s);
                }, $insertRow)) .
                ")" .
                (empty($updateRow)
                    ? ''
                    : "ON DUPLICATE KEY UPDATE " .
                    join(',', array_map(function ($field) use ($updateRow) {
                        return "`$field`=" . self::escape($updateRow[$field]);
                    }, array_keys($updateRow)))) . ";\n";
        }

        DB::unprepared($sql);
    }

    /**
     * Inserts or updates single row
     *
     * @param $insertRow
     * @param $updateRow
     */
    public function insertUpdateSingle(array $insertRow, array $updateRow = [])
    {
        $this->insertUpdateMultiple([$insertRow], [$updateRow]);
    }

    public static function qryAddSortPagination(
        $qry,
        $sort_by = null,
        $sort_ord = 'ASC',
        $page_num = 1,
        $entries_per_page = null
    ) {
        return $qry .
            ($sort_by !== null
                ? self::prepare(
                    ' ORDER BY {!! SORT_BY !!} {!! SORT_ORD !!} ',
                    [
                        'SORT_BY' => $sort_by,
                        'SORT_ORD' => $sort_ord,
                    ]
                )
                : ''
            ) .
            ($entries_per_page
                ? self::prepare(
                    " LIMIT  {!! IDX_START !!}, {!! IDX_END !!}",
                    [
                        'IDX_START' => ($page_num - 1) * $entries_per_page,
                        'IDX_END' => $entries_per_page * 1
                    ]
                )
                : ''
            );
    }

    public static function prepare($qry, array $arr_bindings = [])
    {
        $delimiters = ['!!}' => '{!!', '}}' => '{{'];
        $rx_delimiters =
            '/((?:' . implode(')|(?:', array_merge(array_keys($delimiters), array_values($delimiters))) . '))/';
        $tokens = preg_split($rx_delimiters, $qry, -1, PREG_SPLIT_DELIM_CAPTURE);
        $stack = [];
        foreach ($tokens as $ndx => $token) {
            switch ($token) {
                case '}}':
                case '!!}':
                    $top = array_pop($stack);
                    $top = preg_replace_callback(
                        '/\s*([^\s\|]+)[\s\|]*(.*)/s',
                        function ($match) use ($arr_bindings, $token) {
                            return (isset($arr_bindings[$match[1]])
                                ? (is_array($arr_bindings[$match[1]])
                                    ? ' (' . implode(', ', array_map(
                                        function ($str) use ($token) {
                                            return ($token == '}}' ? self::escape($str) : $str);
                                        },
                                        $arr_bindings[$match[1]]
                                    )) . ') '
                                    : ($token == '}}'
                                        ? self::escape($arr_bindings[$match[1]])
                                        : $arr_bindings[$match[1]]
                                    )
                                )
                                : (trim($match[2]) !== ''
                                    ? trim($match[2])
                                    : "<<<{$match[1]}>>>"
                                )
                            );
                        },
                        $top
                    );

                    if (array_pop($stack) !== $delimiters[$token]) {
                        $tokens[$ndx + 1] = '<<< ' . $tokens[$ndx + 1] . ' >>>';
                        return implode('', $tokens);
                    }

                    $stack[] = array_pop($stack) . $top;
                    break;

                default:
                    $stack[] =
                        ($stack && !in_array($stack[count($stack) - 1], $delimiters) && !in_array($token, $delimiters)
                            ? array_pop($stack)
                            : ''
                        ) .
                        $token;
            }
        }

        return $stack[0];
    }

    public static function sql($sqlfile, array $arr_bindings = [])
    {
        extract($arr_bindings);
        ob_start();
        require(resource_path('queries/' . $sqlfile . '.sql'));
        $retval = ob_get_contents();
        ob_end_clean();
        return self::prepare($retval, $arr_bindings);
    }

    public function __get($name)
    {
        return $this->model->$name;
    }

    public function __set($name, $value)
    {
        $this->model->$name = $value;
    }

    public static function exec($qry)
    {
        try {
            DB::unprepared('START TRANSACTION;');
            $queries = App\Helpers\SqlFormatter::splitQuery($qry);
            foreach ($queries as $qry) {
                DB::unprepared($qry);
            }
            DB::unprepared('COMMIT;');
        } catch (\Exception $e) {
            DB::unprepared('ROLLBACK;');
            throw $e;
        }
    }

    public static function execPrepared($qry, array $arr_bindings = [])
    {
        self::exec(self::prepare($qry, $arr_bindings));
    }

    public static function execSql($sqlfile, array $arr_bindings = [])
    {
        self::exec(self::sql($sqlfile, $arr_bindings));
    }

    public static function selectSql($sqlfile, array $arr_bindings = [])
    {
        return DB::select(self::sql($sqlfile, $arr_bindings));
    }

    public static function paginateSql(
        $sql_file,
        array $arr_bindings = [],
        $sort_by = null,
        $sort_ord = 'ASC',
        $page_num = 1,
        $entries_per_page = 10
    ) {
        $total_rows = (int)DB::select(self::sql(
            $sql_file,
            array_merge($arr_bindings, ['COLUMNS' => 'COUNT(*) AS `count`'])
        ))[0]->count;

        $total_pages = $entries_per_page ? max(ceil($total_rows / $entries_per_page), 1) : 1;

        $page_num = min(max(1, $page_num), $total_pages);

        return [
            'page_num' => $page_num,
            'total_rows' => $total_rows,
            'total_pages' => $entries_per_page ? max(ceil($total_rows / $entries_per_page), 1) : 1,
            'rows' => DB::select(self::qryAddSortPagination(
                self::sql($sql_file, $arr_bindings),
                $sort_by,
                $sort_ord,
                $page_num,
                $entries_per_page
            ))
        ];
    }

    public function getRandomOrCreateFake()
    {
        $class = get_class($this->model);
        $num = $class::count();
        return $num && ($model = $this->model->find(random_int(1, $num)))
            ? $model
            : factory(get_class($this->model))->create();
    }
}
