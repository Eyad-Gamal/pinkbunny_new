<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'address_id'     => 'nullable|uuid|exists:addresses,id',
            'payment_method' => 'required|in:cash_on_delivery,instapay,vodafone_cash',
            'coupon_code'    => 'nullable|string|max:50',
            'notes'          => 'nullable|string|max:500',

            // New address fields — required only when no existing address is selected
            'label'          => 'required_without:address_id|nullable|string|max:100',
            'full_name'      => 'required_without:address_id|nullable|string|max:150',
            'phone'          => 'required_without:address_id|nullable|string|max:20',
            'street'         => 'required_without:address_id|nullable|string|max:500',
            'city'           => 'required_without:address_id|nullable|string|max:100',
            'governorate'    => 'required_without:address_id|nullable|string|max:100',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            if ($this->address_id) {
                $exists = auth()->user()->addresses()->where('id', $this->address_id)->exists();
                if (!$exists) {
                    $v->errors()->add('address_id', 'Invalid address.');
                }
            }
            if (auth()->user()->cartItems()->count() === 0) {
                $v->errors()->add('cart', 'Your cart is empty.');
            }
        });
    }
}
