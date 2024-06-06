<?php

namespace App\Http\Services;

use App\Dto\Category\CategoryCreateDto;
use App\Dto\Category\CategoryFilterDto;
use App\Dto\Category\CategoryUpdateDto;
use App\Models\Category;
use App\Repositories\Category\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


class CategoryService {

    protected CategoryRepositoryInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param CategoryFilterDto $categoryDto
     * @return Collection|LengthAwarePaginator
     */
    public function index(CategoryFilterDto $categoryDto): Collection|LengthAwarePaginator
    {
       return $this->categoryRepository->getAll($categoryDto);
    }

    /**
     * @param int $id
     * @return Category
     */
    public function show(int $id): Category
    {
        return $this->categoryRepository->findById($id);
    }

    /**
     * @param CategoryCreateDto $categoryDto
     * @return Category
     */
    public function store(CategoryCreateDto $categoryDto): Category
    {
        return $this->categoryRepository->create($categoryDto);
    }

    /**
     * @param int $id
     * @param CategoryUpdateDto $categoryDto
     * @return Category
     */
    public function update(int $id, CategoryUpdateDto $categoryDto): Category
    {
        return $this->categoryRepository->update($id, $categoryDto);
    }

    /**
     * @param int $id
     * @return void
     */
    public function destroy(int $id): void
    {
        $this->categoryRepository->delete($id);
    }

}
