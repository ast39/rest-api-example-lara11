<?php

namespace App\Http\Services;

use App\Enums\EOrderReverse;
use App\Exceptions\ReviewNotFoundException;
use App\Models\Review;
use App\Models\Scopes\ReviewScope;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


class ReviewService {

    /**
     * @param array $data
     * @return Collection|LengthAwarePaginator
     * @throws BindingResolutionException
     */
    public function index(array $data): Collection|LengthAwarePaginator
    {
        $order = $data['order'] ?? 'created_at';
        $reverse = $data['reverse'] ?? EOrderReverse::ASC->value;

        $filter = app()->make(ReviewScope::class, [
            'queryParams' => array_filter($data)
        ]);

        $review = Review::query()->filter($filter)
            ->orderBy($order, $reverse);

        return is_null($data['limit'] ?? null)
            ? $review->get()
            : $review->paginate($data['limit']);
    }

    /**
     * @param int $id
     * @return Review
     * @throws ReviewNotFoundException
     */
    public function show(int $id): Review
    {
        $review = Review::find($id);

        if (!$review) {
            throw new ReviewNotFoundException();
        }

        return $review;
    }

    /**
     * @param array $data
     * @return Review
     */
    public function store(array $data): Review
    {
        $review = Review::create($data);

        $review->images()->attach(collect($data)->get('images'));

        return $review;
    }

    /**
     * @param int $id
     * @param array $data
     * @return Review
     * @throws ReviewNotFoundException
     */
    public function update(int $id, array $data): Review
    {
        $review = Review::find($id);

        if (!$review) {
            throw new ReviewNotFoundException();
        }

        $review->update($data);

        $review->images()->sync(collect($data)->get('images'));

        return $review;
    }

    /**
     * @param int $id
     * @return void
     * @throws ReviewNotFoundException
     */
    public function destroy(int $id): void
    {
        $review = Review::find($id);

        if (!$review) {
            throw new ReviewNotFoundException();
        }

        $review->delete();
    }

}
