<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'document_id',
        'action',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Log an action.
     */
    public static function log(int $userId, string $action, ?int $documentId = null, ?array $metadata = null): static
    {
        return static::create([
            'user_id' => $userId,
            'document_id' => $documentId,
            'action' => $action,
            'metadata' => $metadata,
        ]);
    }
}
