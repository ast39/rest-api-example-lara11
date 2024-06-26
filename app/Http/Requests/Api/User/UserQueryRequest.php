<?php

namespace App\Http\Requests\Api\User;

use App\Enums\EOrderReverse;
use App\Enums\ESoftStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;


class UserQueryRequest extends FormRequest {

    public function prepareForValidation()
    {
        if (is_null($this->order)) {
            $this->merge(['order' => 'email']);
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
            'status' => ['sometimes', new Enum(ESoftStatus::class)],
            'page' => ['sometimes', 'integer', 'min:1'],
            'limit' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'order' => ['sometimes', 'string', 'min:1', 'max:100'],
            'reverse' => ['sometimes', new Enum(EOrderReverse::class)],
        ];
    }
}
