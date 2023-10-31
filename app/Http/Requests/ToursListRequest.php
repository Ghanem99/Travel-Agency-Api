<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ToursListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'priceFrom' => 'nullable|numeric|min:0',
            'priceTo' => 'nullable|numeric|min:0',
            'dateFrom' => 'nullable|date',
            'dateTo' => 'nullable|date',
            'sortOrder' => Rule::in(['asc', 'desc']),
            'sortBy' => Rule::in(['price']),
        ];
    }

    public function messages()
    {
        return [
            'sortBy' => 'The sort field must be one of the following types: price',
            'sortOrder' => 'The sort order must be one of the following types: asc, desc'
        ];
    }
}
