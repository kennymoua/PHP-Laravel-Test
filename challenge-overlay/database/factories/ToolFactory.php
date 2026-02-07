<?php

namespace Database\Factories;

use App\Models\Tool;
use Illuminate\Database\Eloquent\Factories\Factory;

class ToolFactory extends Factory
{
    protected $model = Tool::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'status' => Tool::STATUS_AVAILABLE,
        ];
    }

    public function checkedOut(): self
    {
        return $this->state(fn () => ['status' => Tool::STATUS_CHECKED_OUT]);
    }

    public function maintenance(): self
    {
        return $this->state(fn () => ['status' => Tool::STATUS_MAINTENANCE]);
    }
}
