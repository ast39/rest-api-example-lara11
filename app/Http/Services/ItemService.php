<?php

namespace App\Http\Services;

use App\Dto\Item\ItemCreateDto;
use App\Dto\Item\ItemFilterDto;
use App\Dto\Item\ItemUpdateDto;
use App\Models\Item;
use App\Repositories\Item\ItemRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


class ItemService {

    protected ItemRepositoryInterface $itemRepository;

    public function __construct(ItemRepositoryInterface $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    /**
     * @param ItemFilterDto $itemDto
     * @return Collection|LengthAwarePaginator
     */
    public function index(ItemFilterDto $itemDto): Collection|LengthAwarePaginator
    {
        return $this->itemRepository->getAll($itemDto);
    }

    /**
     * @param int $id
     * @return Item
     */
    public function show(int $id): Item
    {
        return $this->itemRepository->findById($id);
    }

    /**
     * @param ItemCreateDto $itemDto
     * @return Item
     */
    public function store(ItemCreateDto $itemDto): Item
    {
        return $this->itemRepository->create($itemDto);
    }

    /**
     * @param int $id
     * @param ItemUpdateDto $itemDto
     * @return Item
     */
    public function update(int $id, ItemUpdateDto $itemDto): Item
    {
        return $this->itemRepository->update($id, $itemDto);
    }

    /**
     * @param int $id
     * @return void
     */
    public function destroy(int $id): void
    {
        $this->itemRepository->delete($id);
    }

}
