<?php
namespace App\Http\Middleware;

use Closure;

class ApiResponder
{
    public function handle($request, Closure $next, $guard = null)
    {
        switch (true) {
            case (strpos($request->getPathInfo(), '/api/') !== false):
                $response = $next($request);
                return ($response->original === null
                    ? response(['success' => true])
                    : $response
                );

            default:
                return $next($request);
        }
    }
}
