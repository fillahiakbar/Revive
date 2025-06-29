<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable implements FilamentUser
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    // ⬇️ Tambahkan ini
    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        // Hanya izinkan email tertentu
        return $this->email === 'fillahi099@gmail.com';

    }
}
