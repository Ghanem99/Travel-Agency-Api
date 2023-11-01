<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Travel;
use App\Http\Resources\TravelResource;
use App\Http\Requests\TravelRequest;

class TravelController extends Controller
{
    public function store(TravelRequest $request)
    {
        $travel = Travel::create($request->validated());
        return new TravelResource($travel);
    }
    
    public function update(TravelRequest $request, Travel $travel)
    {
        $travel->update($request->validated());
        return new TravelResource($travel);
    }
}
