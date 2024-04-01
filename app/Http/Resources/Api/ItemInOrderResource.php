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
 *    schema="ItemInOrderResource",
 *    title="Карточка товара в заказе",
 *    @OA\Property(title="ID", property="id", type="integer", format="int64", example="1"),
 *    @OA\Property(title="Артикул", property="article", type="string", example="ABCDEF"),
 *    @OA\Property(title="Заголовок", property="title", type="string", example="Test"),
 *    @OA\Property(title="Описание", property="body", type="string", example="Test description"),
 *    @OA\Property(title="Категория", property="category", ref="#/components/schemas/CategoryResource"),
 *    @OA\Property(title="Цена", property="price", type="decimal", example="599.90"),
 *  )
 */
class ItemInOrderResource extends JsonResource {

    public static $wrap = 'data';


    public function toArray(Request $request): array
    {
        return [

            'id'  => $this->id,
            'article' => $this->article,
            'title' => $this->title,
            'body' => $this->body,
            'category_id' => $this->category_id,
            'price' => $this->price,
            'order_price' => $this->pivot->price,
        ];
    }
}
