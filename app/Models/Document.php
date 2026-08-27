<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Document extends Model
{
    protected $fillable = [
        'user_id',
        'original_filename',
        'storage_path',
        'mime_type',
        'file_size',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function findings(): HasMany
    {
        return $this->hasMany(Finding::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function highSeverityCount(): int
    {
        return $this->findings()->where('severity', 'high')->count();
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isScanning(): bool
    {
        return $this->status === 'scanning';
    }

    public function isComplete(): bool
    {
        return $this->status === 'complete';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }
}
