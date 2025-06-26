<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'title','mal_id', 'choice','description', 'image',
        'type', 'duration', 'year', 'quality', 'episodes',
        'order', 'is_active'
    ];
}
