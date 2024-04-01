<?php

namespace App\Http\Requests\Api\Order;

use App\Enums\EOrderReverse;
use App\Enums\EOrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;


class OrderQueryRequest extends FormRequest {

    public function prepareForValidation()
    {
        if (!is_null($this->user)) {
            $this->merge(['user' => explode(',', $this->user)]);
        }

        if (!is_null($this->status)) {
            $this->merge(['status' => explode(',', $this->status)]);
        }

        if (is_null($this->order)) {
            $this->merge(['order' => 'created_at']);
        }

        if (is_null($this->reverse)) {
            $this->merge(['reverse' => EOrderReverse::DESC->value]);
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
            'user' => ['sometimes', 'array'],
            'user.*' => ['sometimes', 'integer'],
            'status' => ['sometimes', 'array'],
            'status.*' => ['sometimes', new Enum(EOrderStatus::class)],
            'page' => ['sometimes', 'integer', 'min:1'],
            'limit' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'order' => ['sometimes', 'string', 'min:1', 'max:100'],
            'reverse' => ['sometimes', new Enum(EOrderReverse::class)],
        ];
    }
}
