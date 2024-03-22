<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiUserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
   /** @test */
public function it_can_list_users()
{
    User::factory()->count(10)->create();

    $response = $this->getJson(route('api.users.index'));

    $response->assertStatus(200)
        ->assertJsonCount(10, 'data')
        ->assertJsonStructure(['data' => [['id', 'name', 'email', 'image_path']]]);
}

/** @test */
public function it_can_create_a_user()
{
    $userData = User::factory()->raw();

    $response = $this->postJson(route('api.users.store'), $userData);

    $response->assertStatus(201)
        ->assertJson($userData);
}

/** @test */
public function it_can_show_a_user()
{
    $user = User::factory()->create();

    $response = $this->getJson(route('api.users.show', $user->id));

    $response->assertStatus(200)
        ->assertJson($user->toArray());
}

/** @test */
public function it_can_update_a_user()
{
    $user = User::factory()->create();
    $newUserData = User::factory()->raw();

    $response = $this->putJson(route('api.users.update', $user->id), $newUserData);

    $response->assertStatus(200)
        ->assertJson($newUserData);
}

/** @test */
public function it_can_delete_a_user()
{
    $user = User::factory()->create();

    $response = $this->deleteJson(route('api.users.destroy', $user->id));

    $response->assertStatus(200)
        ->assertJson(['message' => 'Usuario eliminado correctamente']);
}

}
