<?php

namespace Tests\Feature;

use App\Models\Challenge;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiChallengeTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    /** @test */
    public function it_can_list_challenges()
    {
        Challenge::factory()->count(10)->create();
        $response = $this->getJson(route('api.challenges.index'));

        $response->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure(['data' => [['id', 'description', 'difficulty', 'user_id',]]]);
    }
    /** @test */
    public function it_can_create_challenge()
    {
        $user = User::factory()->create();
        $data = [
            'title' => 'Test Challenge',
            'description' => 'This is a test challenge',
            'difficulty' => 5,
            'user_id' => $user->id,
        ];

        $response = $this->postJson(route('api.challenges.store'), $data);

        $response->assertStatus(201)
            ->assertJson($data);
    }
    /** @test */
    public function it_can_show_challenge()
    {
        $challenge = Challenge::factory()->create();
        $response = $this->getJson(route('api.challenges.show', $challenge->id));

        $response->assertStatus(200)
            ->assertJson($challenge->toArray());

    }
    /** @test */
    public function it_can_update_challenge()
    {
        $user = User::factory()->create();
        $challenge = Challenge::factory()->create(['user_id' => $user->id]);

        $data = [
            'title' => 'Updated Challenge',
            'description' => 'This is an updated challenge',
            'difficulty' => 7,
            'user_id' => $user->id,
        ];

        $response = $this->putJson(route('api.challenges.update' , $challenge->id), $data);

        $response->assertStatus(200)
            ->assertJson($data);
    }
    /** @test */
    public function it_can_delete_challenge()
    {
        $challenge = Challenge::factory()->create();

        $response = $this->deleteJson(route('api.challenges.destroy' , $challenge->id));

        $response->assertStatus(200)
            ->assertJson(['message' => 'Desafío eliminado correctamente']);
    }
}
