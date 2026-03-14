<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsNotBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->isBanned()) {
            $bannedUntil = auth()->user()->banned_until;
            auth()->logout();

            if ($bannedUntil->year > 2099) {
                $message = 'Your account has been permanently banned.';
            } else {
                $message = 'Your account has been suspended until ' . $bannedUntil->format('Y-m-d H:i');
            }

            return redirect()->route('login')->withErrors(['email' => $message]);
        }

        return $next($request);
    }
}
