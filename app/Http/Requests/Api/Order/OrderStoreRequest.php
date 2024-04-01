<?php

namespace App\Http\Requests\Api\Order;

use App\Enums\EOrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;


class OrderStoreRequest extends FormRequest {

    public function prepareForValidation(): void
    {
        if (is_null($this->status)) {
            $this->merge(['status' => EOrderStatus::CREATED->value]);
        }

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
            'items' => ['required', 'array'],
            'items.*' => ['required', 'integer', 'exists:items,id'],
        ];
    }
}
