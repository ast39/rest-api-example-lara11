<?php

namespace App\Http\Requests\Api\User;

use App\Enums\ESoftStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;


class UserStoreRequest extends FormRequest {

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

            'name' => ['required', 'string', 'min:2', 'max:128'],
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'confirmed'],
            'status' => ['sometimes', new Enum(ESoftStatus::class)],
        ];
    }
}
