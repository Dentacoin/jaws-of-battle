<?php

namespace App\Http\Middleware;

use Closure;
use App;
use App\Http\Controllers\Controller;

class AdditionalMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        /*if(!isset($_COOKIE['testing-dev'])) {
            return response(view('pages/maintenance'));
        }*/

        $params = $request->route()->parameters();
        return (new App\Http\Controllers\Controller())->minifyHtml($next($request));
    }
}
