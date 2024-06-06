<?php

namespace App\Repositories\Item;

use App\Dto\Item\ItemCreateDto;
use App\Dto\Item\ItemFilterDto;
use App\Dto\Item\ItemUpdateDto;

interface ItemRepositoryInterface {

    public function getAll(ItemFilterDto $itemDto);

    public function findById(int $id);

    public function create(ItemCreateDto $itemDto);

    public function update(int $id, ItemUpdateDto $itemDto);

    public function delete($id): void;
}
