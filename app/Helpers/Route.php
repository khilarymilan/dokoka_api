<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Route as ParentRoute;

class Route extends ParentRoute
{
    public static function parse($str)
    {
        $prefix = null;
        $default_controller = null;
        $method = null;
        $route = null;
        $str = preg_replace('/(\s*\n\s*)+/si', "\n", trim($str));
        $str = preg_replace_callback('/\s([^:\s\n]+\s*:[^\n]+)/si', function ($match) {
            return ';' . preg_replace('/\s/', '', $match[1]);
        }, $str);
        $tokens = preg_split('/\s*[\n\r]+\s*/si', $str);

        foreach ($tokens as $token) {
            switch (true) {
                // comments: # or //
                case preg_match('/^(?:#|\/\/+)/', $token):
                case empty($token):
                    break;

                // [<prefix>  <DefaultController>]
                case preg_match('/^\[\s*([^\s\]]+)?(?:\s+([^\s\]]+))?\]/', $token, $match):
                    $prefix = @$match[1];
                    $default_controller = @$match[2];
                    break;

                // <METHOD> <path> <Controller@method> <alias>(optional)
                default:
                    $token = preg_split('/\s+/', $token, 4, PREG_SPLIT_NO_EMPTY);
                    $methods = preg_split('/\|+/', strtolower(@$token[0]));
                    $path = rtrim(preg_replace('/\/\/+/', '/', '/' . $prefix . '/' . @$token[1]), '/');
                    $controller = preg_replace('/^\*(\@?)/', "{$default_controller}$1", @$token[2]);
                    $alias = trim(@$token[3], ';');

                    foreach ($methods as $method) {
                        if ($method === '*') {
                            if ($alias) {
                                $alias = ";{$alias};";
                                preg_match_all('/;(.*?):/', $alias, $k);
                                preg_match_all('/:(.*?);/', $alias, $v);
                                $alias = array_combine($k[1], $v[1]);
                            }
                            $alias = $alias ? ['names' => $alias, 'only' => array_keys($alias)] : [];
                            self::resource($path, $controller, $alias);
                        } else {
                            $route = self::$method($path, $controller);
                            if ($alias) {
                                $route->name($alias);
                            }
                        }
                    }
            }
        }
    }

    public static function loadFromFile($file)
    {
        self::parse(file_get_contents(base_path('/routes/' . $file)));
    }
}
