<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectAdminLogin
{
    public function handle(Request $request, Closure $next)
    {
        // Jika akses ke admin/login, redirect ke custom login
        if ($request->is('admin/login')) {
            return redirect('/5u34u30');
        }

        return $next($request);
    }
}