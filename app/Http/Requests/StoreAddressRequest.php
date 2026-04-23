<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'label'       => 'required|string|max:50',
            'full_name'   => 'required|string|max:255',
            'phone'       => 'required|string|max:20',
            'street'      => 'required|string|max:500',
            'city'        => 'required|string|max:100',
            'governorate' => 'required|string|max:100',
            'country'     => 'sometimes|string|max:100',
            'is_default'  => 'boolean',
        ];
    }
}
