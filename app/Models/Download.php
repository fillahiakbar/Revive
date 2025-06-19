<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    protected $fillable = [
    'mal_id',
    'episode_number',
    'title',
    'links',
    'is_spotlight',
    ];

    protected $casts = [
        'links' => 'array',
        'is_spotlight' => 'boolean',
    ];
}
