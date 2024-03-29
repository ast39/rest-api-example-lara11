<?php

namespace App\Http\Requests\Api\Item;

use App\Enums\ESoftStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;


class ItemUpdateRequest extends FormRequest {

    public function prepareForValidation(): void
    {
        if (!is_null($this->images)) {
            $this->merge(['images' => explode(',', $this->images)]);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [

            'article' => ['sometimes', 'string', 'min:3', 'max:128'],
            'title' => ['sometimes', 'string', 'min:3', 'max:128'],
            'body' => ['sometimes', 'string', 'max:1000'],
            'category_id' => ['sometimes', 'integer', 'exists:categories,id'],
            'price' => ['sometimes', 'decimal'],
            'status' => ['sometimes', 'decimal'],
            'images' => ['sometimes'],
            'images.*' => ['sometimes', 'integer', 'exists:images,id'],
        ];
    }
}
