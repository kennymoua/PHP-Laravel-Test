<?php

namespace Database\Factories;

use App\Models\Tool;
use App\Models\ToolCheckout;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ToolCheckoutFactory extends Factory
{
    protected $model = ToolCheckout::class;

    public function definition(): array
    {
        return [
            'tool_id' => Tool::factory(),
            'checked_out_at' => Carbon::now()->subHours(2),
            'checked_in_at' => null,
        ];
    }

    public function checkedIn(): self
    {
        return $this->state(fn () => ['checked_in_at' => Carbon::now()->subHour()]);
    }
}
