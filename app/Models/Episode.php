<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    protected $fillable = [
        'mal_id', 'episode_number', 'mp4_arabic_link', 'gdrive_link', 'mp4upload_link', 'torrent_link'
    ];
}