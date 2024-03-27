<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;


class CategoryResource extends JsonResource {

    public static $wrap = 'data';


    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     *
     * @OA\Schema(
     *    type="object",
     *    schema="CategoryResource",
     *    title="Карточка категории",
     *    @OA\Property(title="ID", property="id", type="integer", format="int64", example="1"),
     *    @OA\Property(title="Заголовок", property="title", type="string", example="Test"),
     *    @OA\Property(title="Статус", property="status", type="integer", example="1"),
     *    @OA\Property(title="Создана", property="created", type="datetime", example="2023-12-01 12:00:00"),
     *    @OA\Property(title="Обновлена", property="updated", type="datetime", example="2023-12-01 12:00:00")
     *  )
     */
    public function toArray(Request $request): array
    {
        return [

            'id'  => $this->id ?? null,
            'title' => $this->title ?? null,
            'status' => $this->status ?? null,
            'created' => $this->created_at ?? null,
            'updated' => $this->updated_at ?? null,
        ];
    }
}
