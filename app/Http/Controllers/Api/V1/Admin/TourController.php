<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Travel;
use App\Http\Resources\TourResource;
use App\Http\Requests\TourRequest;

class TourController extends Controller
{
    public function store(TourRequest $request, Travel $travel)
    {
        $tour = $travel->tours()->create($request->validated());
        return new TourResource($tour);
    }
}
