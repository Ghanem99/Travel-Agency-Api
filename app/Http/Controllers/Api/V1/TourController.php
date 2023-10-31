<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ToursListRequest;
use App\Http\Resources\TourResource;
use App\Models\Travel;

class TourController extends Controller
{
    public function index(Travel $travel, ToursListRequest $request)
    {
        $tours = $travel->tours()
            ->when($request->priceFrom, function ($query) use ($request) {
                $query->where('price', '>=', $request->priceFrom);
            })
            ->when($request->priceFrom, function ($query) use ($request) {
                $query->where('price', '>=', $request->priceFrom);
            })
            ->when($request->dateFrom, function ($query) use ($request) {
                $query->where('starting_date', '>=', $request->dateFrom);
            })
            ->when($request->dateTo, function ($query) use ($request) {
                $query->where('starting_date', '>=', $request->dateTo);
            })
            ->when($request->sortBy || $request->sortOrder, function ($query) use ($request) {
                $query->orderBy($request->sortBy, $request->sortOrder ?? 'asc');
            })
            ->orderBy('starting_date', 'asc')
            ->paginate();

        return TourResource::collection($tours);
    }
}
