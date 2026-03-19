<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefClick extends Model
{
    protected $fillable = [
        'ref_user_id',
        'anime_id',
        'viewer_ip',
        'viewer_cookie',
        'viewer_user_id',
    ];

    public function refUser()
    {
        return $this->belongsTo(User::class, 'ref_user_id');
    }

    public function viewerUser()
    {
        return $this->belongsTo(User::class, 'viewer_user_id');
    }
}
