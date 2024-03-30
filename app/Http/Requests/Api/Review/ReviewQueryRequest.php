<?php

namespace App\Http\Requests\Api\Review;

use App\Enums\EOrderReverse;
use App\Enums\ESoftStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;


class ReviewQueryRequest extends FormRequest {

    public function prepareForValidation()
    {
        if (!is_null($this->items)) {
            $this->merge(['items' => explode(',', $this->items)]);
        }

        if (!is_null($this->users)) {
            $this->merge(['users' => explode(',', $this->users)]);
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

            'q' => ['sometimes', 'string'],
            'users' => ['sometimes', 'array'],
            'users.*' => ['sometimes', 'integer'],
            'items' => ['sometimes', 'array'],
            'items.*' => ['sometimes', 'integer'],
            'category.*' => ['sometimes', 'integer'],
            'status' => ['sometimes', new Enum(ESoftStatus::class)],
            'page' => ['sometimes', 'integer', 'min:1'],
            'limit' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'order' => ['sometimes', 'string', 'min:1', 'max:100'],
            'reverse' => ['sometimes', new Enum(EOrderReverse::class)],
        ];
    }
}
