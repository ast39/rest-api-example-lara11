<?php

namespace App\Http\Services;

use App\Dto\ItemDto;
use App\Models\Item;
use App\Repositories\ItemRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


class ItemService {

    protected ItemRepositoryInterface $itemRepository;

    public function __construct(ItemRepositoryInterface $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    /**
     * @param array $data
     * @return Collection|LengthAwarePaginator
     */
    public function index(array $data): Collection|LengthAwarePaginator
    {
        return $this->itemRepository->getAll($data);
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
     * @param array $data
     * @return Item
     */
    public function store(array $data): Item
    {
        return $this->itemRepository->create((array) $data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Item
     */
    public function update(int $id, array $data): Item
    {
        return $this->itemRepository->update($id, (array) $data);
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
