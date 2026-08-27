<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'original_filename' => fake()->word() . '.txt',
            'storage_path' => 'documents/' . fake()->uuid() . '.txt',
            'mime_type' => 'text/plain',
            'file_size' => fake()->numberBetween(100, 10240),
            'status' => 'pending',
        ];
    }

    public function complete(): static
    {
        return $this->state(['status' => 'complete']);
    }

    public function scanning(): static
    {
        return $this->state(['status' => 'scanning']);
    }

    public function failed(): static
    {
        return $this->state(['status' => 'failed']);
    }
}
