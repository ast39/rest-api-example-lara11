<?php

namespace App\Http\Requests\Api\Order;

use App\Enums\EOrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;


class OrderUpdateRequest extends FormRequest {

    public function prepareForValidation(): void
    {
        if (!is_null($this->items)) {
            $this->merge(['items' => explode(',', $this->items)]);
        }
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'body' => ['sometimes', 'string', 'max:1000'],
            'status' => ['sometimes', 'integer', new Enum(EOrderStatus::class)],
            'items' => ['sometimes'],
            'items.*' => ['sometimes', 'integer', 'exists:items,id'],
        ];
    }
}
