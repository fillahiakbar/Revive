<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\View;
use App\Models\SocialMedia;


class FortifyCustomProvider extends ServiceProvider
{
   public function register(): void
{
    $this->app->singleton(LoginResponse::class, RedirectUserAfterLogin::class);
    $this->app->singleton(RegisterResponse::class, RedirectUserAfterRegister::class);
}

    public function boot(): void
    {
           View::composer('*', function ($view) {
            $view->with('socialMedias', SocialMedia::where('is_active', true)->get());
        });
    }
}
