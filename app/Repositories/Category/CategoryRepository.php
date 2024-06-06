<?php

namespace App\Repositories\Category;

use App\Dto\Category\CategoryCreateDto;
use App\Dto\Category\CategoryFilterDto;
use App\Dto\Category\CategoryUpdateDto;
use App\Enums\EOrderReverse;
use App\Exceptions\CategoryNotFoundException;
use App\Models\Category;
use App\Models\Scopes\CategoryScope;
use Illuminate\Contracts\Container\BindingResolutionException;

class CategoryRepository implements CategoryRepositoryInterface {

    protected Category $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    /**
     * Список категорий
     *
     * @param CategoryFilterDto $categoryDto
     * @return mixed
     * @throws BindingResolutionException
     */
    public function getAll(CategoryFilterDto $categoryDto)
    {
        $order = $data['order'] ?? 'title';
        $reverse = $data['reverse'] ?? EOrderReverse::ASC->value;

        $filter = app()->make(CategoryScope::class, [
            'queryParams' => array_filter($categoryDto->toArray())
        ]);

        $categories = $this->model::query()->filter($filter)
            ->orderBy($order, $reverse);

        return is_null($itemDto->limit ?? null)
            ? $categories->get()
            : $categories->paginate($itemDto->limit);
    }

    /**
     * Получить категорию по ID
     *
     * @param int $id
     * @return Category
     * @throws CategoryNotFoundException
     */
    public function findById(int $id): Category
    {
        $category = $this->model::find($id);

        if (!$category) {
            throw new CategoryNotFoundException();
        }

        return $category;
    }

    /**
     * Добавить категорию
     *
     * @param CategoryCreateDto $categoryDto
     * @return Category
     */
    public function create(CategoryCreateDto $categoryDto): Category
    {
        return $this->model::create(collect($categoryDto)->toArray());
    }

    /**
     * Обновить категорию
     *
     * @param int $id
     * @param CategoryUpdateDto $categoryDto
     * @return Category
     * @throws CategoryNotFoundException
     */
    public function update(int $id, CategoryUpdateDto $categoryDto): Category
    {
        $category = $this->model::find($id);

        if (!$category) {
            throw new CategoryNotFoundException();
        }

        return $category->update($categoryDto->toArray());
    }

    /**
     * Удаление категории
     *
     * @param $id
     * @return void
     * @throws CategoryNotFoundException
     */
    public function delete($id): void
    {
        $category = $this->model::find($id);

        if (!$category) {
            throw new CategoryNotFoundException();
        }

        $category->delete();
    }
}
