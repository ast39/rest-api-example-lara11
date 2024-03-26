<?php

namespace App\Http\Requests\Api\Item;

use App\Enums\EItemStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ItemStoreRequest extends FormRequest {

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

            'title' => ['required', 'string'],
            'body' => ['sometimes', 'string'],
            'price' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'],
            'category' => ['required', 'integer', 'exists:categories,id'],
            'status' => ['sometimes', new Enum(EItemStatus::class)],
        ];
    }
}
