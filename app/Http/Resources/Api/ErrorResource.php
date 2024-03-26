<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;


class ErrorResource extends JsonResource {

    public static $wrap = 'error';

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [

            'status' => false,
            'code' => $this->code ?? 500,
            'msg'  => $this->message ?? null,
        ];
    }

    public function jsonOptions()
    {
        return JSON_UNESCAPED_UNICODE;
    }
}
