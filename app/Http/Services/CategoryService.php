<?php

namespace App\Http\Services;

use App\Enums\EOrderReverse;
use App\Exceptions\CategoryNotFoundException;
use App\Models\Category;
use App\Models\Scopes\CategoryScope;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


class CategoryService {

    /**
     * @param array $data
     * @param string|null $order
     * @param string|null $reverse
     * @return Collection|LengthAwarePaginator
     * @throws BindingResolutionException
     */
    public function index(array $data, ?string $order = 'title', ?string $reverse = EOrderReverse::ASC->value): Collection|LengthAwarePaginator
    {
        $filter = app()->make(CategoryScope::class, [
            'queryParams' => array_filter($data)
        ]);

        $categories = Category::query()->filter($filter)
            ->orderBy($order, $reverse);

        return is_null($data['limit'] ?? null)
            ? $categories->get()
            : $categories->paginate($data['limit']);
    }

    /**
     * @param int $id
     * @return Category
     * @throws CategoryNotFoundException
     */
    public function show(int $id): Category
    {
        $category = Category::find($id);

        if (!$category) {
            throw new CategoryNotFoundException();
        }

        return $category;
    }

    /**
     * @param array $data
     * @return Category
     */
    public function store(array $data): Category
    {
        return Category::create($data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Category
     * @throws CategoryNotFoundException
     */
    public function update(int $id, array $data): Category
    {
        $category = Category::findOrFail($id);

        if (!$category) {
            throw new CategoryNotFoundException();
        }

        $category->update($data);

        return $category;
    }

    /**
     * @param int $id
     * @return void
     * @throws CategoryNotFoundException
     */
    public function destroy(int $id): void
    {
        $category = Category::find($id);

        if (!$category) {
            throw new CategoryNotFoundException();
        }

        $category->delete();
    }

}
