<?php

namespace App\Http\Requests\Api\Item;

use App\Enums\EOrderReverse;
use App\Enums\ESoftStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;


class ItemQueryRequest extends FormRequest {

    public function prepareForValidation()
    {
        if (!is_null($this->category)) {
            $this->merge(['category' => explode(',', $this->category)]);
        }

        if (is_null($this->order)) {
            $this->merge(['order' => 'title']);
        }

        if (is_null($this->reverse)) {
            $this->merge(['reverse' => EOrderReverse::ASC->value]);
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
            'category' => ['sometimes', 'array'],
            'category.*' => ['sometimes', 'integer'],
            'status' => ['sometimes', new Enum(ESoftStatus::class)],
            'page' => ['sometimes', 'integer', 'min:1'],
            'limit' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'order' => ['sometimes', 'string', 'min:1', 'max:100'],
            'reverse' => ['sometimes', new Enum(EOrderReverse::class)],
        ];
    }
}
