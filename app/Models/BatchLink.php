<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchLink extends Model
{
    protected $fillable = ['batch_id', 'resolution', 'url_torrent', 'url_mega', 'url_gdrive', 'url_megaHard', 'url_gdriveHard',];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
