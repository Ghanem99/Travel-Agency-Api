<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Travel;
use App\Models\Tour;

class ToursListTest extends TestCase
{
    use RefreshDatabase;

    public function test_tours_list_by_travel_slug_returns_correct_tours() : void 
    {
        $travel = Travel::factory()->create();
        $tour = Tour::factory()->create([
            'travel_id' => $travel->id,
        ]);

        $response = $this->get('/api/v1/travels/' . $travel->slug . '/tours');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['id' => $tour->id]);
    }

    public function test_tour_price_is_show_correctly(): void 
    {
        $travel = Travel::factory()->create();
        Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 123.45,
        ]);

        $response = $this->get('/api/v1/travels/' . $travel->slug . '/tours');

        $response->assertStatus(200);
        $response->assertJsonFragment(['price' => 123.45]);
    }

    public function test_tours_list_returns_paginated_list(): void 
    {
        $travel = Travel::factory()->create();
        Tour::factory()->count(16)->create([
            'travel_id' => $travel->id,
        ]);

        $response = $this->get('/api/v1/travels/' . $travel->slug . '/tours');

        $response->assertStatus(200);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonPath('meta.last_page', 2);
    }


}
