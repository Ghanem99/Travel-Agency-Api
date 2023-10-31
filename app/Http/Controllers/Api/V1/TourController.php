<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Travel;
use App\Http\Resources\TourResource;

use Illuminate\Validation\Rule;


class TourController extends Controller
{
    public function index(Travel $travel, Request $request)
    {
        $request->validate([
            'priceFrom' => 'nullable|numeric|min:0',
            'priceTo' => 'nullable|numeric|min:0',
            'dateFrom' => 'nullable|date',
            'dateTo' => 'nullable|date',
            'sortOrder' => Rule::in(['asc', 'desc']),
            'sortBy' => Rule::in(['price']),
        ], [
            'sortBy' => 'The sort field must be one of the following types: price',
            'sortOrder' => 'The sort order must be one of the following types: asc, desc'
        ]);

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
