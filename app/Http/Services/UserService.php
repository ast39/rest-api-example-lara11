<?php

namespace App\Http\Services;

use App\Exceptions\UserNotFoundException;
use App\Http\Resources\Api\UserResource;
use App\Models\Scopes\UserScope;
use App\Models\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Resources\Json\JsonResource;


class UserService {

    /**
     * @param array $data
     * @return JsonResource
     * @throws BindingResolutionException
     */
    public function index(array $data): JsonResource
    {
        $filter = app()->make(UserScope::class, [
            'queryParams' => array_filter($data)
        ]);

        $users = User::query()->filter($filter)
            ->orderBy('title');

        $users = is_null($data['limit'] ?? null)
            ? $users->get()
            : $users->paginate($data['limit']);

        return UserResource::collection($users);
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function getByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * @param int $id
     * @return JsonResource
     * @throws UserNotFoundException
     */
    public function show(int $id): JsonResource
    {
        $user = User::find($id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    /**
     * @param array $data
     * @return JsonResource
     */
    public function store(array $data): JsonResource
    {
        $data['password'] = bcrypt($data['password']);

        return User::create($data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return JsonResource
     * @throws UserNotFoundException
     */
    public function update(int $id, array $data): JsonResource
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
     * @return JsonResource
     * @throws UserNotFoundException
     */
    public function destroy(int $id): JsonResource
    {
        $user = User::find($id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        $user->delete();

        return $user;
    }
}
