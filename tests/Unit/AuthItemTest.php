<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\SystemAuthItem;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthItemTest extends TestCase
{
    use RefreshDatabase;
    private $token;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $getToken = $this->postJson('/api/authenticate',[
            'email' => $user->email,
            'password'  => 'password',
            'app_name' => 'testing'
        ]);
        $this->token = $getToken->getContent();
    }

    public function test_get_all_endpoint_can_be_accessed(){
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/system/auth-item/all');
        $response
            ->assertStatus(200);
    }

    protected function create_one(){
        $response = $this->postJson('/api/system/auth-item/store',[
            'name'  => 'name_test',
            'type'  => 1,
            'description'   => 'description_test'
        ]);
        $idCreated = $response->decodeResponseJson()['data']['id'];
        return $idCreated;
    }

    public function test_get_one_endpoint_can_be_accessed(){
        $authItem = SystemAuthItem::factory()->create();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/system/auth-item/one/1');
        $response
            ->assertStatus(200)
            ->assertJsonPath('data.id', 1)
            ->assertJsonPath('data.name', $authItem->name)
            ->assertJsonPath('data.type', $authItem->type)
            ->assertJsonPath('data.description', $authItem->description);
    }

    public function test_auth_item_can_be_created(){
        $response = $this->postJson('/api/system/auth-item/store',[
            'name'  => 'name_test',
            'type'  => 1,
            'description'   => 'description_test'
        ]);
        $response->assertStatus(200);
        $idCreated = $response->decodeResponseJson()['data']['id'];
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/system/auth-item/one/' . $idCreated);
        $response
            ->assertStatus(200)
            ->assertJsonPath('data.id', $idCreated)
            ->assertJsonPath('data.name', 'name_test')
            ->assertJsonPath('data.type', 1)
            ->assertJsonPath('data.description', 'description_test');
    }

    public function test_auth_item_can_be_updated(){
        $idCreated = $this->create_one();

        $response = $this->putJson('/api/system/auth-item/' . $idCreated,[
            'description'   => 'newly_updated_description_test'
        ]);
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/system/auth-item/one/' . $idCreated);
        $response
            ->assertStatus(200)
            ->assertJsonPath('data.id', $idCreated)
            ->assertJsonPath('data.name', 'name_test')
            ->assertJsonPath('data.type', 1)
            ->assertJsonPath('data.description', 'newly_updated_description_test');
    }

    public function test_auth_item_can_be_deleted(){
        $idCreated = $this->create_one();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->deleteJson('/api/system/auth-item/' . $idCreated);
        $response->assertStatus(200);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/system/auth-item/one/' . $idCreated);
        $response
            ->assertStatus(200)
            ->assertJsonPath('data.id', NULL);
    }
}
