<?php

namespace App\Http\Services;

use App\Enums\EOrderReverse;
use App\Exceptions\OrderNotFoundException;
use App\Models\Order;
use App\Models\Scopes\OrderScope;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


class OrderService {

    /**
     * @param array $data
     * @return Collection|LengthAwarePaginator
     * @throws BindingResolutionException
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
     * @throws OrderNotFoundException
     */
    public function show(int $id): Order
    {
        $order = Order::find($id);

        if (!$order) {
            throw new OrderNotFoundException();
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

        $order->items()->attach(collect($data)->get('items'));

        return $order;
    }

    /**
     * @param int $id
     * @param array $data
     * @return Order
     * @throws OrderNotFoundException
     */
    public function update(int $id, array $data): Order
    {
        $order = Order::find($id);

        if (!$order) {
            throw new OrderNotFoundException();
        }

        $order->update($data);

        $order->items()->sync(collect($data)->get('items'));

        return $order;
    }

    /**
     * @param int $id
     * @return void
     * @throws OrderNotFoundException
     */
    public function destroy(int $id): void
    {
        $Order = Order::find($id);

        if (!$Order) {
            throw new OrderNotFoundException();
        }

        $Order->delete();
    }

}
