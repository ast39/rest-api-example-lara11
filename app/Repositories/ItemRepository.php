<?php

namespace App\Repositories;

use App\Enums\EOrderReverse;
use App\Exceptions\ItemNotFoundException;
use App\Models\Item;
use App\Models\Scopes\ItemScope;

class ItemRepository implements ItemRepositoryInterface {

    protected Item $model;

    public function __construct(Item $model)
    {
        $this->model = $model;
    }

    public function getAll(array $attributes)
    {
        $order = $data['order'] ?? 'title';
        $reverse = $data['reverse'] ?? EOrderReverse::ASC->value;

        $filter = app()->make(ItemScope::class, [
            'queryParams' => array_filter($attributes)
        ]);

        $items = $this->model::query()->filter($filter)
            ->orderBy($order, $reverse);

        return is_null($attributes['limit'] ?? null)
            ? $items->get()
            : $items->paginate($attributes['limit']);
    }

    /**
     * Получить товар по ID
     *
     * @param int $id
     * @return Item
     * @throws ItemNotFoundException
     */
    public function findById(int $id): Item
    {
        $item = $this->model::find($id);

        if (!$item) {
            throw new ItemNotFoundException();
        }

        return $item;
    }

    /**
     * Добавить товар
     *
     * @param array $attributes
     * @return Item
     */
    public function create(array $attributes): Item
    {
        $item = $this->model::create($attributes);

        $item->images()->attach(collect($attributes)->get('images'));

        return $item;
    }

    /**
     * Обновить товар
     *
     * @param int $id
     * @param array $attributes
     * @return Item
     * @throws ItemNotFoundException
     */
    public function update(int $id, array $attributes): Item
    {
        $item = $this->model::find($id);

        if (!$item) {
            throw new ItemNotFoundException();
        }

        $item->update($attributes);

        $item->images()->sync(collect($attributes)->get('images'));

        return $item;
    }

    /**
     * Удаление товара
     *
     * @param $id
     * @return void
     * @throws ItemNotFoundException
     */
    public function delete($id): void
    {
        $item = $this->model::find($id);

        if (!$item) {
            throw new ItemNotFoundException();
        }

        $item->delete();
    }
}
