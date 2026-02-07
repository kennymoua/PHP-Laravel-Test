<?php

namespace Tests\Feature;

use App\Models\Tool;
use App\Models\ToolCheckout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToolCheckoutApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_current_endpoint_returns_only_active_checkouts(): void
    {
        // Active checkouts (should be returned)
        $c1 = ToolCheckout::factory()->create(['checked_in_at' => null]);
        $c1->tool()->update(['status' => Tool::STATUS_CHECKED_OUT]);

        $c2 = ToolCheckout::factory()->create(['checked_in_at' => null]);
        $c2->tool()->update(['status' => Tool::STATUS_CHECKED_OUT]);

        // Checked-in checkout (should NOT be returned)
        $c3 = ToolCheckout::factory()->checkedIn()->create();
        $c3->tool()->update(['status' => Tool::STATUS_AVAILABLE]);

        $resp = $this->getJson('/api/tool-checkouts/current');

        $resp->assertOk()
            ->assertJsonStructure(['data'])
            ->assertJsonCount(2, 'data');

        // Ensure each item includes tool info
        $first = $resp->json('data.0');
        $this->assertArrayHasKey('tool', $first);
        $this->assertArrayHasKey('status', $first['tool']);
    }

    public function test_check_in_updates_checkout_and_tool_status(): void
    {
        $checkout = ToolCheckout::factory()->create(['checked_in_at' => null]);
        $checkout->tool()->update(['status' => Tool::STATUS_CHECKED_OUT]);

        $resp = $this->patchJson('/api/tool-checkouts/' . $checkout->id . '/check-in');

        $resp->assertOk()
            ->assertJsonPath('data.id', $checkout->id);

        $checkout->refresh();
        $checkout->tool->refresh();

        $this->assertNotNull($checkout->checked_in_at);
        $this->assertSame(Tool::STATUS_AVAILABLE, $checkout->tool->status);
    }

    public function test_check_in_fails_if_already_checked_in(): void
    {
        $checkout = ToolCheckout::factory()->checkedIn()->create();
        $checkout->tool()->update(['status' => Tool::STATUS_AVAILABLE]);

        $resp = $this->patchJson('/api/tool-checkouts/' . $checkout->id . '/check-in');

        $resp->assertStatus(422)
            ->assertJsonStructure(['message']);
    }
}
