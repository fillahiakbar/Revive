<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchLink extends Model
{
    protected $fillable = ['batch_id', 'resolution', 'codec', 'url_torrent', 'url_rr_torrent', 'url_mega', 'url_gdrive', 'url_megaHard', 'url_gdriveHard', 'url_pixeldrain'];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
