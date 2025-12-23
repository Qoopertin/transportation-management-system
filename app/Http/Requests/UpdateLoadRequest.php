<?php

namespace App\Http\Requests;

use App\Enums\LoadStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLoadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update loads');
    }

    public function rules(): array
    {
        return [
            'pickup_address' => ['sometimes', 'string', 'max:1000'],
            'delivery_address' => ['sometimes', 'string', 'max:1000'],
            'pickup_at' => ['sometimes', 'nullable', 'date'],
            'delivery_at' => ['sometimes', 'nullable', 'date', 'after:pickup_at'],
            'status' => ['sometimes', Rule::in(array_column(LoadStatus::cases(), 'value'))],
            'assigned_driver_id' => ['sometimes', 'nullable', 'exists:users,id'],
            'notes' => ['sometimes', 'nullable', 'string', 'max:5000'],
        ];
    }
}
