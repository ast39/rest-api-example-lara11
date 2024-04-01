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
 *    schema="OrderResource",
 *    title="Карточка заказа",
 *    @OA\Property(title="ID", property="id", type="integer", format="int64", example="1"),
 *    @OA\Property(title="Автор заказа", property="user", ref="#/components/schemas/UserResource"),
 *    @OA\Property(title="Примечание", property="body", type="string", example="Test description"),
 *    @OA\Property(title="Сумма заказа", property="amount", type="decimal", example="1499.90"),
 *    @OA\Property(title="Позиций в заказе", property="volume", type="integer", format="int64", example="5"),
 *    @OA\Property(title="Текущий статус", property="status", ref="#/components/schemas/OrderLogResource"),
 *    @OA\Property(title="Создан", property="created", type="datetime", example="2023-12-01 12:00:00"),
 *    @OA\Property(title="Обновлен", property="updated", type="datetime", example="2023-12-01 12:00:00"),
 *    @OA\Property(title="Состав заказа", property="items", type="array", @OA\Items(ref="#/components/schemas/ItemResource")),
 *    @OA\Property(title="Лог заказа", property="log", type="array", @OA\Items(ref="#/components/schemas/OrderLogResource"))
 *  )
 */
class OrderResource extends JsonResource {

    public static $wrap = 'data';


    public function toArray(Request $request): array
    {
        return [

            'id'  => $this->id ?? null,
            'user' => UserResource::make($this->user),
            'body' => $this->body ?? null,
            'amount' => $this->amount ?? null,
            'volume' => $this->volume ?? null,
            'status' => OrderLogResource::make(collect($this->log)->last()),
            'created' => $this->created_at ?? null,
            'updated' => $this->updated_at ?? null,
            'items' => ItemInOrderResource::collection($this->items),
            'log' => OrderLogResource::collection($this->log),

        ];
    }
}
