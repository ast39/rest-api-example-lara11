<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use OpenApi\Annotations as OA;


class RoleResource extends ApiResource {

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     *
     * @OA\Schema(
     *   type="object",
     *   schema="RoleResource",
     *   title="Карточка роли",
     *   @OA\Property(title="ID", property="id", type="integer", format="int64", example="1"),
     *   @OA\Property(title="Название", property="title", type="string", example="Test User"),
     *   @OA\Property(title="Описание", property="note", type="string", example="test@test.com"),
     *   @OA\Property(title="Создана", property="created", type="datetime", example="2023-12-01 12:00:00"),
     *   @OA\Property(title="Обновлена", property="updated", type="datetime", example="2023-12-01 12:00:00")
     * )
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,
            'title' => $this->title,
            'note' => $this->note,
            'created' => $this->created_at,
            'updated' => $this->updated_at,
        ];
    }
}
