<?php

namespace App\Http\Requests\Api\Order;

use Illuminate\Foundation\Http\FormRequest;


class OrderStoreRequest extends FormRequest {

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
            'items' => ['required', 'array'],
            'items.*' => ['required', 'integer', 'exists:items,id'],
        ];
    }
}
