<?php

namespace App\Http\Requests\Api\Review;

use App\Enums\ESoftStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ReviewQueryRequest extends FormRequest {

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

            'item_id' => ['nullable', 'integer', 'exists:items,id'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'body' => ['nullable', 'string', 'max:1000'],
            'status' => ['nullable', 'integer', new Enum(ESoftStatus::class)],
        ];
    }
}
