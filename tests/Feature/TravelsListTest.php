<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use App\Models\Travel;

class TravelsListTest extends TestCase
{
    use RefreshDatabase;

    public function test_travels_list_returns_paginated_data_correctly(): void
    {
        Travel::factory()->count(16)->create([
            'is_public' => true,
        ]);

        $response = $this->get('/api/v1/travels');
        $response->assertStatus(200);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonPath('meta.last_page', 2);

    }

    public function test_travels_list_shows_only_public_records() : void 
    {
        $public_record = Travel::factory()->count()->create([
            'is_public' => true,
        ]);
        Travel::factory()->count()->create([
            'is_public' => false,
        ]);

        $response = $this->get('/api/v1/travels');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('meta.0.name', $public_record->name);
    }

}
