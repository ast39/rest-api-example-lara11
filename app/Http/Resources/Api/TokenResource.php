<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;


class TokenResource extends JsonResource {

    public static $wrap = 'data';


    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     *
     * @OA\Schema(
     *    type="object",
     *    schema="TokenResource",
     *    title="Персональный токен",
     *    @OA\Property(title="Токен", property="token", type="string", example="1|abcdefjhijklmn"),
     *  )
     */
    public function toArray(Request $request): array
    {
        return [
            //
        ];
    }
}
