<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_access_endpoint_missing_authorization_header(){
        $response = $this->getJson('/api/system/auth-item');
        $response
            ->assertStatus(404);
    }

    public function test_access_endpoint_invalid_authorization_header(){
        $response = $this->withHeaders([
            'Authorization' => 'Bearer wrongbearerauthtoken'
        ])->getJson('/api/system/auth-item/all');
        $response
            ->assertStatus(401);
    }

    public function test_access_endpoint_valid_authorization_header(){
        $user = User::factory()->create();
        $getToken = $this->postJson('/api/authenticate',[
            'email' => $user->email,
            'password'  => 'password',
            'app_name' => 'testing'
        ]);
        $getToken->assertStatus(200);
        $getToken->assertSee('|');
        $token = $getToken->getContent();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/system/auth-item/all');
        $response
            ->assertStatus(200);
    }
}
