<?php

namespace App\Http\Requests\Api\Review;

use App\Enums\ERate;
use App\Enums\ESoftStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;


class ReviewStoreRequest extends FormRequest {

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

            'item_id' => ['required', 'integer', 'exists:items,id'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'rate' => ['required', new Enum(ERate::class)],
            'body' => ['sometimes', 'string', 'max:1000'],
            'status' => ['sometimes', 'integer', new Enum(ESoftStatus::class)],
            'images' => ['sometimes'],
            'images.*' => ['sometimes', 'integer', 'exists:images,id'],
        ];
    }
}
