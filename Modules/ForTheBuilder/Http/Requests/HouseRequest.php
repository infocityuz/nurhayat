<?php

namespace Modules\ForTheBuilder\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HouseRequest extends BaseFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function store()
    {
        return [
            'name' => 'nullable|string|max:255',
            'description'  => 'required|string|max:255',
            'corpus'  => 'nullable|string|max:255',
            'entrance_count' => 'required|integer|max:1000',
            'floor_count' => 'required|integer|max:10000',
            'project_stage' => 'required|integer|max:10000',
            'total_flat' => 'required|integer|max:10000',
            'entrance_one_floor_count' => 'required|integer|max:10000',
            'has_basement' => 'nullable|string',
            'has_attic' => 'nullable|string',
            'house_number' => 'nullable|integer|max:10000',

            'company' => 'nullable|string',
            'address' => 'nullable|string',
            'settlement' => 'nullable|string',
            'bank' => 'nullable|string',
            'mfo' => 'nullable|string',
            'inn' => 'nullable|string',
            'oked' => 'nullable|string',
            'phone' => 'nullable|string',
            'director' => 'nullable|string',
        ];
    }

    public function update()
    {
        return [
            'name' => 'nullable|string|max:255',
            'description'  => 'required|string|max:255',
            'corpus'  => 'nullable|string|max:255',
            // 'entrance_count' => 'required|integer|max:1000',
            // 'floor_count' => 'required|integer|max:10000',
            'project_stage' => 'required|integer|max:10000',
            // 'total_flat' => 'required|integer|max:10000',
            // 'entrance_one_floor_count' => 'required|integer|max:10000',
            // 'has_basement' => 'nullable|string',
            // 'has_attic' => 'nullable|string',
            'house_number' => 'nullable|integer|max:10000',

            'company' => 'nullable|string',
            'address' => 'nullable|string',
            'settlement' => 'nullable|string',
            'bank' => 'nullable|string',
            'mfo' => 'nullable|string',
            'inn' => 'nullable|string',
            'oked' => 'nullable|string',
            'phone' => 'nullable|string',
            'director' => 'nullable|string',
        ];
    }
}
