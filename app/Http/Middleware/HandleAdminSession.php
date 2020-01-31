<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\Admin\MainController;

class HandleAdminSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $admin_controller = new MainController();
        if(!$admin_controller->checkLogin() && !array_key_exists('username', $request->input()) && !array_key_exists('password', $request->input())) {
            //NOT LOGGED AND NOT TRYING TO LOG IN
            return response($admin_controller->getAdminAccess());
        }

        $response = $next($request);
        $response->headers->set('Referrer-Policy', 'no-referrer');
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        return $response;
    }

}
