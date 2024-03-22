<?php

namespace Tests\Feature;

use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiProgramTest extends TestCase
{
    /**
     * A basic feature test example.
     */
     /**
     * Test para obtener una lista paginada de programas.
     *
     * @return void
     */
    public function test_can_get_paginated_programs()
    {
        Program::factory()->count(15)->create();

        $response = $this->getJson(route('api.programs.index'));

        $response->assertStatus(200)
                 ->assertJsonCount(10, 'data')
                 ->assertJsonStructure([
                    'data' => [
                        '*' => ['id', 'title', 'description', 'start_date', 'end_date', 'user_id', 'created_at', 'updated_at']
                    ]
                 ]);
    }

    /**
     * Test para crear un nuevo programa.
     *
     * @return void
     */
    public function test_can_create_program()
    {
        $user = User::factory()->create();

        $data = [
            'title' => 'New Program',
            'description' => 'This is a new program',
            'start_date' => '2024-03-15',
            'end_date' => '2024-03-20',
            'user_id' => $user->id,
        ];

        $response = $this->postJson(route('api.programs.store'), $data);

        $response->assertStatus(201)
                 ->assertJson($data);
    }

    /**
     * Test para obtener un programa específico.
     *
     * @return void
     */
    public function test_can_get_program()
    {
        $program = Program::factory()->create();

        $response = $this->getJson(route('api.programs.show',$program->id));

        $response->assertStatus(200)
                 ->assertJson($program->toArray());
    }

    /**
     * Test para actualizar un programa existente.
     *
     * @return void
     */
    public function test_can_update_program()
    {
        $program = Program::factory()->create();

        $data = [
            'title' => 'Updated Program',
            'description' => 'This is an updated program',
            'start_date' => '2024-03-20',
            'end_date' => '2024-03-25',
            'user_id' => $program->user_id,
        ];

        $response = $this->putJson(route('api.programs.update',$program->id), $data);

        $response->assertStatus(200)
                 ->assertJson($data);
    }

    /**
     * Test para eliminar un programa existente.
     *
     * @return void
     */
    public function test_can_delete_program()
    {
        $program = Program::factory()->create();

        $response = $this->deleteJson(route('api.programs.destroy',$program->id));

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Program deleted successfully']);

        $this->assertDatabaseMissing('programs', ['id' => $program->id]);
    }
}
