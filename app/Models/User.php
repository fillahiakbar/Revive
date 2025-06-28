<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Laravel\Jetstream\HasProfilePhoto;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasProfilePhoto;
    use Notifiable;
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public function comments()
{
    return $this->hasMany(Comment::class);
}
}