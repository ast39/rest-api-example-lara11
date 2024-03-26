<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;


class MessageResource extends JsonResource {

    public static $wrap = 'data';

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => true,
        ];
    }

    public function jsonOptions()
    {
        return JSON_UNESCAPED_UNICODE;
    }
}
