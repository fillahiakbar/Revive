<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'content',
        'instruction',
        'icon',
        'qr_code', // Add QR Code field
        'is_active',
        'options',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'options' => 'array',
    ];
}
