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

    public function test_tours_list_sorts_by_starting_date_correctly(): void 
    {
        $travel = Travel::factory()->create();
        $laterTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'starting_date' => now()->addDays(10),
            'ending_date' => now()->addDays(20),
        ]);
        $earlierTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'starting_date' => now()->addDays(5),
            'ending_date' => now()->addDays(15),
        ]);

        $response = $this->get('/api/v1/travels/' . $travel->slug . '/tours');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonPath('data.0.id', $earlierTour->id);
        $response->assertJsonPath('data.1.id', $laterTour->id);
    }

    public function test_tours_list_sorts_by_price_correctly(): void 
    {
        $travel = Travel::factory()->create();
        $expensiveTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 200.00,
        ]);
        $cheaperLaterTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 100.00,
            'starting_date' => now()->addDays(10),
            'ending_date' => now()->addDays(20),
        ]);
        $cheaperEarlierTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 100.00,
            'starting_date' => now()->addDays(5),
            'ending_date' => now()->addDays(15),
        ]);

        $response = $this->get('/api/v1/travels/' . $travel->slug . '/tours');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
        $response->assertJsonPath('data.0.id', $cheaperEarlierTour->id);
        $response->assertJsonPath('data.1.id', $cheaperLaterTour->id);
        $response->assertJsonPath('data.2.id', $expensiveTour->id);
    }

    public function test_tours_list_filters_by_price_correctly(): void 
    {
        $travel = Travel::factory()->create();
        $expensiveTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 200.00,
        ]);
        $cheaperLaterTour = Tour::factory()->create([
            'travel_id' => $travel->id,
            'price' => 100.00,
        ]);

        $endPoint = '/api/v1/travels/' . $travel->slug . '/tours';

        $response = $this->get($endPoint . '?priceFrom=100');
        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['id' => $expensiveTour->id]);
        $response->assertJsonFragment(['id' => $cheaperLaterTour->id]);

        $response = $this->get($endPoint . '?priceFrom=150');
        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonMissing(['id' => $expensiveTour->id]);
        $response->assertJsonFragment(['id' => $cheaperLaterTour->id]);

        $response = $this->get($endPoint . '?priceTo=250');
        $response->assertJsonCount(0, 'data');

        $response = $this->get($endPoint . '?priceTo=200');
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['id' => $expensiveTour->id]);

    }


}
