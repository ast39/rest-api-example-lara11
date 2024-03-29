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
 *    schema="ItemResource",
 *    title="Карточка товара",
 *    @OA\Property(title="ID", property="id", type="integer", format="int64", example="1"),
 *    @OA\Property(title="Артикул", property="article", type="string", example="ABCDEF"),
 *    @OA\Property(title="Заголовок", property="title", type="string", example="Test"),
 *    @OA\Property(title="Описание", property="body", type="string", example="Test description"),
 *    @OA\Property(title="Категория", property="category", ref="#/components/schemas/ItemResource"),
 *    @OA\Property(title="Цена", property="price", type="decimal", example="599.90"),
 *    @OA\Property(title="Статус", property="status", type="integer", example="1"),
 *    @OA\Property(title="Создана", property="created", type="datetime", example="2023-12-01 12:00:00"),
 *    @OA\Property(title="Обновлена", property="updated", type="datetime", example="2023-12-01 12:00:00")
 *  )
 */
class ItemResource extends JsonResource {

    public static $wrap = 'data';


    public function toArray(Request $request): array
    {
        return [

            'id'  => $this->id ?? null,
            'article' => $this->article ?? null,
            'title' => $this->title ?? null,
            'body' => $this->body ?? null,
            'category' => CategoryResource::make($this->category),
            'price' => $this->price ?? null,
            'status' => $this->status ?? null,
            'created' => $this->created_at ?? null,
            'updated' => $this->updated_at ?? null,
            'images' => ImageResource::collection($this->images),
        ];
    }
}
