<?php

namespace App\Http\Services;

use App\Enums\EOrderReverse;
use App\Enums\EOrderStatus;
use App\Exceptions\NotAuthorizedException;
use App\Exceptions\OrderNotFoundException;
use App\Models\Item;
use App\Models\Order;
use App\Models\Scopes\OrderScope;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


class OrderService {

    /**
     * @param array $data
     * @return Collection|LengthAwarePaginator
     * @throws BindingResolutionException|NotAuthorizedException
     */
    public function index(array $data): Collection|LengthAwarePaginator
    {
        $order = $data['order'] ?? 'title';
        $reverse = $data['reverse'] ?? EOrderReverse::ASC->value;

        $filter = app()->make(OrderScope::class, [
            'queryParams' => array_filter($data)
        ]);

        $orders = Order::query()->filter($filter)
            ->orderBy($order, $reverse);

        return is_null($data['limit'] ?? null)
            ? $orders->get()
            : $orders->paginate($data['limit']);
    }

    /**
     * @param int $id
     * @return Order
     * @throws OrderNotFoundException|NotAuthorizedException
     */
    public function show(int $id): Order
    {
        $order = Order::find($id);

        if (!$order) {
            throw new OrderNotFoundException();
        }

        if (request()->user()->cannot('view', $order)) {
            throw new NotAuthorizedException();
        }

        return $order;
    }

    /**
     * @param array $data
     * @return Order
     */
    public function store(array $data): Order
    {
        $order = Order::create($data);

        foreach ($data['items'] as $item) {
            $price = Item::query()->where('id', 1)->pluck('price')->collect()->get(0);
            $order->items()->attach($item, ['price' => $price]);
        }

        $order->log()->create(['status_id' => EOrderStatus::CREATED->value]);

        return $order;
    }

    /**
     * @param int $id
     * @param array $data
     * @return Order
     * @throws OrderNotFoundException|NotAuthorizedException
     */
    public function update(int $id, array $data): Order
    {
        $order = Order::find($id);

        if (!$order) {
            throw new OrderNotFoundException();
        }

        if (request()->user()->cannot('update', $order)) {
            throw new NotAuthorizedException();
        }

        $order->update(collect($data)->except('items')->toArray());

        $pivotData = [];
        if ($items = collect($data)->get('items')) {
            foreach ($items as $item) {
                $pivotData[] = ['price' => Item::query()->where('id', $item)->pluck('price')->collect()->get(0)];
            }
            $syncData = array_combine(collect($data)->get('items'), $pivotData);
        }
        $order->items()->sync($syncData ?? collect($data)->get('items'));

        if (collect($data)->get('status')) {
            $order->log()->create(['status_id' => $data['status']]);
        }

        return $order;
    }

    /**
     * @param int $id
     * @return void
     * @throws OrderNotFoundException|NotAuthorizedException
     */
    public function destroy(int $id): void
    {
        $order = Order::find($id);

        if (!$order) {
            throw new OrderNotFoundException();
        }

        if (request()->user()->cannot('delete', $order)) {
            throw new NotAuthorizedException();
        }

        $order->delete();
    }

}
