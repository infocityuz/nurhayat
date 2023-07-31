<?php

namespace Modules\ForTheBuilder\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HouseFlatRequest extends BaseFormRequest
{
    public function authorize()
    {
        return true;
    }

    public function store()
    {
        return [
            'number_of_flat' => 'required|integer|max:1000000',
            'entrance' => 'required|integer|max:1000000',
            'room_count' => 'nullable|integer|max:1000000',
            'floor' => 'nullable|integer|max:1000000',
            'area_housing' => 'required|numeric|between:0,100000000.99',
            'area_basement' => 'nullable|numeric|between:0,100000000.99',
            'area_terraca' => 'nullable|numeric|between:0,100000000.99',
            'area_attic' => 'nullable|numeric|between:0,100000000.99',
            'area_balcony' => 'nullable|numeric|between:0,100000000.99',
            'area_total' => 'required|numeric|between:0,100000000.99',
            'area_bedroom' => 'required|numeric|between:0,100000000.99',
            'area_bedroom_2' => 'nullable|numeric|between:0,100000000.99',
            'area_bedroom_3' => 'nullable|numeric|between:0,100000000.99',
            'area_bedroom_4' => 'nullable|numeric|between:0,100000000.99',
            'area_bedroom_5' => 'nullable|numeric|between:0,100000000.99',
            'area_hotel' => 'required|numeric|between:0,100000000.99',
            'area_hotel_2' => 'nullable|numeric|between:0,100000000.99',
            'area_hotel_3' => 'nullable|numeric|between:0,100000000.99',
            'area_hotel_4' => 'nullable|numeric|between:0,100000000.99',
            'area_hotel_5' => 'nullable|numeric|between:0,100000000.99',
            'area_kitchen' => 'nullable|numeric|between:0,100000000.99',
            'area_corridor' => 'nullable|numeric|between:0,100000000.99',
            'area_bathroom' => 'nullable|numeric|between:0,100000000.99',
            'area_other' => 'nullable|numeric|between:0,100000000.99',
            'doc_number' => 'required|string|max:255',
            'price' => 'nullable|numeric|between:0,1000000000000.99',
            'price_30' => 'nullable|numeric|between:0,100000000000.99',
            'price_50' => 'nullable|numeric|between:0,100000000000.99',
            'price_70' => 'nullable|numeric|between:0,100000000000.99',

            'price_initial_payment' => 'nullable|numeric|between:0,1000000000000.99',
            'price_30_initial_payment' => 'nullable|numeric|between:0,100000000000.99',
            'price_50_initial_payment' => 'nullable|numeric|between:0,100000000000.99',
            'price_70_initial_payment' => 'nullable|numeric|between:0,100000000000.99',

            'price_basement' => 'nullable|numeric|between:0,100000000000.99',
            'price_basement_30' => 'nullable|numeric|between:0,100000000000.99',
            'price_basement_50' => 'nullable|numeric|between:0,100000000000.99',
            'price_basement_70' => 'nullable|numeric|between:0,100000000000.99',
            'price_attic' => 'nullable|numeric|between:0,100000000000.99',
            'price_attic_30' => 'nullable|numeric|between:0,100000000000.99',
            'price_attic_50' => 'nullable|numeric|between:0,100000000000.99',
            'price_attic_70' => 'nullable|numeric|between:0,100000000000.99',
            'price_terrace' => 'nullable|numeric|between:0,100000000000.99',
            'price_terrace_30' => 'nullable|numeric|between:0,100000000000.99',
            'price_terrace_50' => 'nullable|numeric|between:0,100000000000.99',
            'price_terrace_70' => 'nullable|numeric|between:0,100000000000.99',
            'files' => 'nullable',
            'files' => 'mimes:jpeg,jpg,png,pdf,doc,docx,xls,xlsx|max:40960',
        ];
    }

    public function update()
    {
        return [
            'number_of_flat' => 'required|integer|max:1000000',
            'entrance' => 'required|integer|max:1000000',
            'room_count' => 'nullable|integer|max:1000000',
            'floor' => 'nullable|integer|max:1000000',
            'area_housing' => 'nullable|numeric|between:0,100000000.99',
            'area_basement' => 'nullable|numeric|between:0,100000000.99',
            'area_terraca' => 'nullable|numeric|between:0,100000000.99',
            'area_attic' => 'nullable|numeric|between:0,100000000.99',
            'area_balcony' => 'nullable|numeric|between:0,100000000.99',
            'area_total' => 'required|numeric|between:0,100000000.99',
            'area_bedroom' => 'required|numeric|between:0,100000000.99',
            'area_bedroom_2' => 'nullable|numeric|between:0,100000000.99',
            'area_bedroom_3' => 'nullable|numeric|between:0,100000000.99',
            'area_bedroom_4' => 'nullable|numeric|between:0,100000000.99',
            'area_bedroom_5' => 'nullable|numeric|between:0,100000000.99',
            'area_hotel' => 'required|numeric|between:0,100000000.99',
            'area_hotel_2' => 'nullable|numeric|between:0,100000000.99',
            'area_hotel_3' => 'nullable|numeric|between:0,100000000.99',
            'area_hotel_4' => 'nullable|numeric|between:0,100000000.99',
            'area_hotel_5' => 'nullable|numeric|between:0,100000000.99',
            'area_kitchen' => 'nullable|numeric|between:0,100000000.99',
            'area_corridor' => 'nullable|numeric|between:0,100000000.99',
            'area_bathroom' => 'nullable|numeric|between:0,100000000.99',
            'area_other' => 'nullable|numeric|between:0,100000000.99',
            'doc_number' => 'required|string|max:255',
            'price' => 'nullable|numeric|between:0,1000000000000.99',
            'price_30' => 'nullable|numeric|between:0,100000000000.99',
            'price_50' => 'nullable|numeric|between:0,100000000000.99',
            'price_70' => 'nullable|numeric|between:0,100000000000.99',

            'price_initial_payment' => 'nullable|numeric|between:0,1000000000000.99',
            'price_30_initial_payment' => 'nullable|numeric|between:0,100000000000.99',
            'price_50_initial_payment' => 'nullable|numeric|between:0,100000000000.99',
            'price_70_initial_payment' => 'nullable|numeric|between:0,100000000000.99',
            
            'price_basement' => 'nullable|numeric|between:0,100000000000.99',
            'price_basement_30' => 'nullable|numeric|between:0,100000000000.99',
            'price_basement_50' => 'nullable|numeric|between:0,100000000000.99',
            'price_basement_70' => 'nullable|numeric|between:0,100000000000.99',
            'price_attic' => 'nullable|numeric|between:0,100000000000.99',
            'price_attic_30' => 'nullable|numeric|between:0,100000000000.99',
            'price_attic_50' => 'nullable|numeric|between:0,100000000000.99',
            'price_attic_70' => 'nullable|numeric|between:0,100000000000.99',
            'price_terrace' => 'nullable|numeric|between:0,100000000000.99',
            'price_terrace_30' => 'nullable|numeric|between:0,100000000000.99',
            'price_terrace_50' => 'nullable|numeric|between:0,100000000000.99',
            'price_terrace_70' => 'nullable|numeric|between:0,100000000000.99',
            'files' => 'nullable',
            'files' => 'mimes:jpeg,jpg,png,pdf,doc,docx,xls,xlsx|max:40960',
        ];
    }
}
