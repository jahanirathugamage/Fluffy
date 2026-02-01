<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    public function test_protected_route_requires_authentication()
    {
        $response = $this->getJson('/api/v1/products');
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_access_protected_route()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->getJson('/api/v1/products');
        $response->assertStatus(200);
    }
}
