<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;


/**
 * Transform the resource into an array.
 *
 * @OA\Schema(
 *    type="object",
 *    schema="ReviewResource",
 *    title="Карточка отзыва",
 *    @OA\Property(title="ID", property="id", type="integer", format="int64", example="1"),
 *    @OA\Property(title="Товар", property="item", ref="#/components/schemas/ItemResource"),
 *    @OA\Property(title="Пользователь", property="user", ref="#/components/schemas/UserResource"),
 *    @OA\Property(title="Оценка", property="rate", type="integer", example="5"),
 *    @OA\Property(title="Описание", property="body", type="string", example="Test description"),
 *    @OA\Property(title="Статус", property="status", type="integer", example="1"),
 *    @OA\Property(title="Создан", property="created", type="datetime", example="2023-12-01 12:00:00"),
 *    @OA\Property(title="Обновлен", property="updated", type="datetime", example="2023-12-01 12:00:00")
 *  )
 */
class ReviewResource extends JsonResource {

    public static $wrap = 'data';


    public function toArray(Request $request): array
    {
        return [

            'id'  => $this->id ?? null,
            'item' => ItemResource::make($this->item),
            'user' => UserResource::make($this->user),
            'rate' => $this->rate ?? null,
            'body' => $this->body ?? null,
            'status' => $this->status ?? null,
            'created' => $this->created_at ?? null,
            'updated' => $this->updated_at ?? null,
            'images' => ImageResource::collection($this->images),
        ];
    }
}
