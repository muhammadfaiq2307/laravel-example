<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthSanctumTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticate_endpoint_return_sanctum_token(){
        $user = User::factory()->create();
        $getToken = $this->postJson('/api/authenticate',[
            'email' => $user->email,
            'password'  => 'password',
            'app_name' => 'testing'
        ]);
        $getToken->assertStatus(200);
        $getToken->assertSee('|');
    }
}
