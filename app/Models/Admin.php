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

    /**
     * Relationship to EmailBlacklist
     */
    public function emailBlacklists()
    {
        return $this->hasMany(EmailBlacklist::class, 'blocked_by');
    }

 
    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        // Hanya izinkan email tertentu
        // return $this->email === 'fartcloud91@gmail.com';
      return in_array($this->email, [
            'fartcloud91@gmail.com',
            'admin@animefn.com',
        ]);
    

    }
}
