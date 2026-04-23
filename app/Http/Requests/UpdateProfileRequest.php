<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function rules(): array
    {
        $userId = auth()->id();
        return [
            'name'  => 'required|string|max:255',
            'email' => "nullable|email|unique:users,email,{$userId}",
            'phone' => "nullable|string|max:20|unique:users,phone,{$userId}",
        ];
    }
}
