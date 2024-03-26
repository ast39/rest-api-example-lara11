<?php

namespace App\Http\Services;

use App\Exceptions\CategoryNotFoundException;
use App\Http\Resources\Api\CategoryResource;
use App\Models\Category;
use App\Models\Scopes\CategoryScope;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Resources\Json\JsonResource;


class CategoryService {

    /**
     * @param array $data
     * @return JsonResource
     * @throws BindingResolutionException
     */
    public function index(array $data): JsonResource
    {
        $filter = app()->make(CategoryScope::class, [
            'queryParams' => array_filter($data)
        ]);

        $categories = Category::query()->filter($filter)
            ->orderBy('title');

        $categories = is_null($data['limit'] ?? null)
            ? $categories->get()
            : $categories->paginate($data['limit']);

        return CategoryResource::collection($categories);
    }

    /**
     * @param int $id
     * @return JsonResource
     * @throws CategoryNotFoundException
     */
    public function show(int $id): JsonResource
    {
        $category = Category::find($id);

        if (!$category) {
            throw new CategoryNotFoundException();
        }

        return CategoryResource::make($category);
    }

    /**
     * @param array $data
     * @return JsonResource
     */
    public function store(array $data): JsonResource
    {
        return Category::create($data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return JsonResource
     * @throws CategoryNotFoundException
     */
    public function update(int $id, array $data): JsonResource
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
     * @return JsonResource
     * @throws CategoryNotFoundException
     */
    public function destroy(int $id): JsonResource
    {
        $category = Category::find($id);

        if (!$category) {
            throw new CategoryNotFoundException();
        }

        $category->delete();

        return $category;
    }

}
