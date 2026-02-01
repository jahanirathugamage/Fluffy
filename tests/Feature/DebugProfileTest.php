<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class DebugProfileTest extends TestCase
{
    public function test_profile_debug_metrics()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/debug-profile');
        $response->dump();
        $response->assertStatus(200);
    }
}
