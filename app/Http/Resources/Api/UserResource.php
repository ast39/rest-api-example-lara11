<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;


class UserResource extends ApiResource {

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created' => $this->created_at,
        ];
    }
}
