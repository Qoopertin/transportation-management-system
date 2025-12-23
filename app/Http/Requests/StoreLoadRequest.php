<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create loads');
    }

    public function rules(): array
    {
        return [
            'pickup_address' => ['required', 'string', 'max:1000'],
            'delivery_address' => ['required', 'string', 'max:1000'],
            'pickup_at' => ['nullable', 'date', 'after:now'],
            'delivery_at' => ['nullable', 'date', 'after:pickup_at'],
            'assigned_driver_id' => ['nullable', 'exists:users,id'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
