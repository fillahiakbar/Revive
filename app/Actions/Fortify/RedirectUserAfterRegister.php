<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\RegisterResponse;
use Illuminate\Http\Request;

class RedirectUserAfterRegister implements RegisterResponse
{
    public function toResponse($request)
    {
        $user = auth()->user();

        return redirect()->intended(
            $user->role === 'admin' ? '/admin' : '/revive'
        );
    }
}
