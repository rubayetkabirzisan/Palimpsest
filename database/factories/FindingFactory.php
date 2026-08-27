<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\Finding;
use Illuminate\Database\Eloquent\Factories\Factory;

class FindingFactory extends Factory
{
    protected $model = Finding::class;

    public function definition(): array
    {
        return [
            'document_id' => Document::factory(),
            'type' => fake()->randomElement(['credit_card', 'ssn', 'email', 'api_key', 'phone']),
            'snippet' => fake()->sentence(),
            'reason' => fake()->sentence(),
            'severity' => fake()->randomElement(['low', 'medium', 'high']),
            'source' => fake()->randomElement(['regex', 'llm']),
            'position' => fake()->numberBetween(0, 1000),
        ];
    }
}
