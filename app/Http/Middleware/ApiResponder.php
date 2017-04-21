<?php
namespace App\Http\Middleware;

use Closure;

class ApiResponder
{
    public function handle($request, Closure $next, $guard = null)
    {
        $response = $next($request);
        return ($response->original === null
            ? response(['success' => true])
            : $response
        );
    }
}
