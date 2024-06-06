<?php

namespace App\Repositories\Item;

use App\Dto\Item\ItemCreateDto;
use App\Dto\Item\ItemFilterDto;
use App\Dto\Item\ItemUpdateDto;
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

    public function getAll(ItemFilterDto $itemDto)
    {
        $order = $data['order'] ?? 'title';
        $reverse = $data['reverse'] ?? EOrderReverse::ASC->value;

        $filter = app()->make(ItemScope::class, [
            'queryParams' => array_filter($itemDto->toArray())
        ]);

        $items = $this->model::query()->filter($filter)
            ->orderBy($order, $reverse);

        return is_null($itemDto->limit ?? null)
            ? $items->get()
            : $items->paginate($itemDto->limit);
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
     * @param ItemCreateDto $itemDto
     * @return Item
     */
    public function create(ItemCreateDto $itemDto): Item
    {
        $item = $this->model::create(collect($itemDto)->toArray());

        $item->images()->attach(collect($itemDto)->get('images'));

        return $item;
    }

    /**
     * Обновить товар
     *
     * @param int $id
     * @param ItemUpdateDto $itemDto
     * @return Item
     * @throws ItemNotFoundException
     */
    public function update(int $id, ItemUpdateDto $itemDto): Item
    {
        $item = $this->model::find($id);

        if (!$item) {
            throw new ItemNotFoundException();
        }

        $item->update($itemDto->toArray());

        $item->images()->sync(collect($itemDto)->get('images'));

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
