<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse;
use Illuminate\Http\Request;

class RedirectUserAfterLogin implements LoginResponse
{
    public function toResponse($request)
    {
        $user = auth()->user();

        return redirect()->intended(
            $user->role === 'admin' ? '/admin' : '/revive'
        );
    }
}
