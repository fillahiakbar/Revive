<?php

namespace App\Http\Middleware;

use App\Models\EmailBlacklist;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBlacklist
{
    /**
     * Check if the authenticated user's email is blacklisted.
     * If so, log them out immediately.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('web')->check()) {
            try {
                $user = Auth::guard('web')->user();

                if ($user && EmailBlacklist::isBlacklisted($user->email)) {
                    Auth::guard('web')->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('login')->with('blacklisted', true);
                }
            } catch (\Exception $e) {
                // Don't block the request if something goes wrong
            }
        }

        return $next($request);
    }
}
