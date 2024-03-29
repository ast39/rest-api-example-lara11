<?php

namespace App\Http\Services;

use App\Enums\EOrderReverse;
use App\Exceptions\UserNotFoundException;
use App\Models\Scopes\UserScope;
use App\Models\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


class UserService {

    /**
     * @param array $data
     * @return Collection|LengthAwarePaginator
     * @throws BindingResolutionException
     */
    public function index(array $data): Collection|LengthAwarePaginator
    {
        $order = $data['order'] ?? 'name';
        $reverse = $data['reverse'] ?? EOrderReverse::ASC->value;

        $filter = app()->make(UserScope::class, [
            'queryParams' => array_filter($data)
        ]);

        $users = User::query()->filter($filter)
            ->orderBy($order, $reverse);

        return is_null($data['limit'] ?? null)
            ? $users->get()
            : $users->paginate($data['limit']);
    }

    /**
     * @param string $email
     * @return User
     * @throws UserNotFoundException
     */
    public function getByEmail(string $email): User
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    /**
     * @param int $id
     * @return User
     * @throws UserNotFoundException
     */
    public function show(int $id): User
    {
        $user = User::find($id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    /**
     * @param array $data
     * @return User
     */
    public function store(array $data): User
    {
        $data['password'] = bcrypt($data['password']);

        return User::create($data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return User
     * @throws UserNotFoundException
     */
    public function update(int $id, array $data): User
    {
        $user = User::findOrFail($id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        $user->update($data);

        return $user;
    }

    /**
     * @param int $id
     * @return void
     * @throws UserNotFoundException
     */
    public function destroy(int $id): void
    {
        $user = User::find($id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        $user->delete();
    }
}
