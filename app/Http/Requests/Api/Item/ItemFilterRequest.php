<?php

namespace App\Http\Requests\Api\Item;

use App\Enums\EItemStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;


class ItemFilterRequest extends FormRequest {

    public function prepareForValidation()
    {
        if (!is_null($this->category)) {
            $this->merge(['category' => explode(',', $this->category)]);
        }

        if (!is_null($this->status)) {
            $this->merge(['status' => explode(',', $this->status)]);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [

            'q' => ['sometimes', 'string'],
            'status' => ['sometimes', 'array'],
            'status.*' => ['sometimes', new Enum(EItemStatus::class)],
            'category' => ['sometimes', 'array'],
            'category.*' => ['sometimes', 'integer', 'exists:categories,id'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'limit' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
