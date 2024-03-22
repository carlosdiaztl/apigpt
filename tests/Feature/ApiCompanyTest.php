<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiCompanyTest extends TestCase
{

    /** @test */
    public function can_create_company()
    {
        $user = User::factory()->create();
        $data = [
            'name' => 'Example Company',
            'location' => 'Example Location',
            'industry' => 'Example Industry',
            'user_id' => $user->id,
        ];

        $response = $this->postJson(route('api.companies.store'), $data);

        $response->assertStatus(201)
            ->assertJson($data);
    }

    /** @test */
    public function can_update_company()
    {
        $user = User::factory()->create();
        $company = Company::factory()->create(['user_id' => $user->id]);
        $data = [
            'name' => 'Updated Company Name',
            'location' => 'Updated Location',
            'industry' => 'Updated Industry',
            'user_id' => $user->id,
        ];

        $response = $this->putJson(route('api.companies.update', ['company' => $company->id]), $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('companies', $data);
    }

    /** @test */
    public function can_delete_company()
    {
        $company = Company::factory()->create();

        $response = $this->deleteJson(route('api.companies.destroy', ['company' => $company->id]));

        $response->assertStatus(200)
            ->assertJson(['message' => 'Empresa eliminada correctamente']);
    }
    /** @test */
    public function can_list_companies_paginated()
    {
        Company::factory()->count(15)->create();

        $response = $this->getJson(route('api.companies.index'));

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
    }

    /** @test */
    public function can_show_company()
    {
        $company = Company::factory()->create();

        $response = $this->getJson(route('api.companies.show', ['company' => $company]));

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $company->id,
            'name' => $company->name,
            'location' => $company->location,
            'industry' => $company->industry,
        ]);
    }
}
