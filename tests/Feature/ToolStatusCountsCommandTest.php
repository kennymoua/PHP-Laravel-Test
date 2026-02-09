<?php

namespace Tests\Feature;

use App\Models\Tool;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToolStatusCountsCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_tools_status_counts_command_outputs_correct_counts(): void
    {
        Tool::factory()->count(2)->create(['status' => Tool::STATUS_AVAILABLE]);
        Tool::factory()->count(3)->create(['status' => Tool::STATUS_CHECKED_OUT]);
        Tool::factory()->count(1)->create(['status' => Tool::STATUS_MAINTENANCE]);

        $this->artisan('tools:status-counts')
            ->expectsOutput('Available: 2')
            ->expectsOutput('Checked Out: 3')
            ->expectsOutput('Maintenance: 1')
            ->assertExitCode(0);
    }
}
