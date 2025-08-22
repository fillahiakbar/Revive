<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'anime_link_id',
        'user_id',
        'body',
        'author',
        'likes',
        'parent_id',
    ];

    // User yang menulis komentar
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Komentar ini milik anime link tertentu
    public function animeLink()
    {
        return $this->belongsTo(AnimeLink::class, 'anime_link_id');
    }

    // Balasan terhadap komentar ini
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->oldest();
    }

    // Jika komentar ini adalah balasan, maka parent-nya:
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
    
}
