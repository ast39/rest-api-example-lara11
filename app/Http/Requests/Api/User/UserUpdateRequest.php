<?php

namespace App\Http\Requests\Api\User;

use App\Enums\ESoftStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;


class UserUpdateRequest extends FormRequest {

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

            'name' => ['sometimes', 'string', 'min:2', 'max:128'],
            'password' => ['sometimes', 'confirmed'],
            'status' => ['sometimes', new Enum(ESoftStatus::class)],
        ];
    }
}
