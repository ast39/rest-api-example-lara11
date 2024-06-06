<?php

namespace App\Repositories\Category;

use App\Dto\Category\CategoryCreateDto;
use App\Dto\Category\CategoryFilterDto;
use App\Dto\Category\CategoryUpdateDto;

interface CategoryRepositoryInterface {

    public function getAll(CategoryFilterDto $categoryDto);

    public function findById(int $id);

    public function create(CategoryCreateDto $categoryDto);

    public function update(int $id, CategoryUpdateDto $categoryDto);

    public function delete($id): void;
}
