<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Finding extends Model
{
    protected $fillable = [
        'document_id',
        'type',
        'snippet',
        'reason',
        'severity',
        'source',
        'position',
    ];

    /**
     * Encrypt the snippet at rest — sensitive data should never be stored in plain text.
     */
    protected function casts(): array
    {
        return [
            'snippet' => 'encrypted',
        ];
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function isHighSeverity(): bool
    {
        return $this->severity === 'high';
    }

    public function redactedSnippet(): string
    {
        return '[REDACTED: ' . $this->reason . ']';
    }
}
