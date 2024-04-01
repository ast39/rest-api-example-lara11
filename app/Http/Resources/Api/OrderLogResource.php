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
 *    schema="OrderLogResource",
 *    title="Карточка лога заказа",
 *    @OA\Property(title="ID", property="id", type="integer", format="int64", example="1"),
 *    @OA\Property(title="ID статуса", property="status_id", type="integer", format="int64", example="1"),
 *    @OA\Property(title="Описание статуса", property="status_text", type="string", example="Test description"),
 *    @OA\Property(title="Создан", property="created", type="datetime", example="2023-12-01 12:00:00"),
 *    @OA\Property(title="Обновлен", property="updated", type="datetime", example="2023-12-01 12:00:00"),
 *  )
 */
class OrderLogResource extends JsonResource {

    public static $wrap = 'data';


    public function toArray(Request $request): array
    {
        return [

            'id'  => $this->id,
            'status_id' => $this->status_id,
            'status_text' => __('msg.order.status.' . $this->status_id),
            'created' => $this->created_at,
            'updated' => $this->updated_at,
        ];
    }
}
