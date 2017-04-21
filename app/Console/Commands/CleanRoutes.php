<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CleanRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recreates the route list & cleans up the web routes files.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach (glob(base_path('routes/web-*.routes')) as $route_file) {
            $orig_contents = file_get_contents($route_file);
            $str = trim($orig_contents);
            $str = preg_replace('/(\s*\n\s*)+/s', "\n", $str);
            $str = preg_replace('/[\t ]+/', ' ', $str);
            $str = preg_replace('/(?:^|\n)(\[[^\]]*\])/', "\n\n$1", $str);
            $str = explode("\n", $str);
            $len_route = [0, 0, 0, 0];
            $new_route = [];
            $new_alias = [];
            foreach ($str as $ndx => $token) {
                switch (true) {
                    // comments: # or //
                    case preg_match('/^(?:#|\/\/+)/', $token):
                    case empty($token):
                        break;

                    // [<prefix>  <DefaultController>]
                    case preg_match('/^\[\s*([^\s\]]+)?\s*(?:([^\]]+))?\s*\]$/', $token, $match):
                        $str[$ndx] = '[' . trim(@$match[1] . '  ' . @$match[2]) . ']';
                        break;

                    // action : alias
                    case preg_match('/^([a-z]+)\s*\:\s*([a-z\-_\d]+)$/i', $token, $match):
                        $new_alias[$ndx] = [strtolower($match[1]), $match[2]];
                        $len_route[3] = max($len_route[3], strlen($match[1]));
                        break;

                    // <METHOD> <path> <Controller@method> <alias>(optional)
                    default:
                        $token = preg_split('/\s+/', $token, 4, PREG_SPLIT_NO_EMPTY);
                        $methods = strtoupper(trim(@$token[0]));
                        $path = rtrim(preg_replace('/\/\/+/', '/', '/' . @$token[1]), '/') ?: '/';
                        $controller = trim(@$token[2]);
                        $alias = trim(@$token[3]);
                        $alias_action = $alias;
                        $alias_name = '';

                        $len_route[0] = max($len_route[0], strlen($methods));
                        $len_route[1] = max($len_route[1], strlen($path));
                        $len_route[2] = max($len_route[2], strlen($controller));

                        if (preg_match('/^([a-z]+)\s*\:\s*([a-z\-_\d]+)$/i', $alias, $match)) {
                            $alias_action = strtolower(trim($match[1]));
                            $alias_name = trim($match[2]);
                            $len_route[3] = max($len_route[3], strlen($alias_action));
                        }

                        $new_route[$ndx] = [$methods, $path, $controller, $alias_action, $alias_name];
                }
            }

            foreach ($new_route as $ndx => $token) {
                $str[$ndx] =
                    trim(
                        str_pad($token[0], $len_route[0] + 2, ' ', STR_PAD_RIGHT) .
                        str_pad($token[1], $len_route[1] + 2, ' ', STR_PAD_RIGHT) .
                        str_pad($token[2], $len_route[2] + 2, ' ', STR_PAD_RIGHT) .
                        str_pad($token[3], $len_route[3] + 1, ' ', STR_PAD_RIGHT) .
                        ': ' .
                        $token[4],
                        ': '
                    );
            }

            foreach ($new_alias as $ndx => $token) {
                $str[$ndx] =
                    str_pad('', $len_route[0] + 2, ' ', STR_PAD_RIGHT) .
                    str_pad('', $len_route[1] + 2, ' ', STR_PAD_RIGHT) .
                    str_pad('', $len_route[2] + 2, ' ', STR_PAD_RIGHT) .
                    trim(
                        str_pad($token[0], $len_route[3] + 1, ' ', STR_PAD_RIGHT) .
                        ': ' .
                        $token[1],
                        ': '
                    );
            }

            $str = join("\n", $str);
            if ($orig_contents !== $str) {
                file_put_contents($route_file, $str);
            }
        }
    }
}
