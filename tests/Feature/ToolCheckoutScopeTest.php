<?php

namespace Tests\Feature;

use App\Models\ToolCheckout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToolCheckoutScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_currently_checked_out_scope_filters_checked_in_at_null(): void
    {
        ToolCheckout::factory()->count(2)->create(['checked_in_at' => null]);
        ToolCheckout::factory()->checkedIn()->create();

        $this->assertSame(2, ToolCheckout::currentlyCheckedOut()->count());
    }
}
