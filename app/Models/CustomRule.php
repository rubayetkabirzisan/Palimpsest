<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomRule extends Model
{
    protected $fillable = [
        'name',
        'description',
        'prompt_instruction',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all active rules as a combined prompt string.
     */
    public static function activePromptInstructions(): string
    {
        $rules = static::where('is_active', true)->get();

        if ($rules->isEmpty()) {
            return '';
        }

        $instructions = $rules->map(fn ($rule) => "- {$rule->name}: {$rule->prompt_instruction}");

        return "\n\nAdditional custom detection rules:\n" . $instructions->implode("\n");
    }
}
