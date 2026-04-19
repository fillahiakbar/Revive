<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailBlacklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'reason',
        'blocked_by',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationship to Admin who blocked this email
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'blocked_by');
    }

    /**
     * Relationship to get the admin name
     */
    public function getAdminNameAttribute(): string
    {
        return $this->admin?->name ?? 'نظام';
    }

    /**
     * Check if an email is blacklisted
     */
    public static function isBlacklisted(string $email): bool
    {
        return static::where('email', $email)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Get the reason why an email is blacklisted
     */
    public static function getBlacklistReason(string $email): ?string
    {
        return static::where('email', $email)
            ->where('is_active', true)
            ->value('reason');
    }

    /**
     * Scope to get only active blacklists
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
