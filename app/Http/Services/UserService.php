<?php

namespace App\Http\Services;

use App\Enums\EOrderReverse;
use App\Exceptions\UserNotFoundException;
use App\Models\Scopes\UserScope;
use App\Models\User;
use App\Repositories\User\UserCommandRepository;
use App\Repositories\User\UserRequestRepository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


class UserService {

    protected UserRequestRepository $repoRequest;
    protected UserCommandRepository $repoCommand;


    public function __construct(
        UserRequestRepository $repoRequest,
        UserCommandRepository $repoCommand
    ) {
        $this->repoRequest = $repoRequest;
        $this->repoCommand = $repoCommand;
    }

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

        return $this->repoRequest->findList($filter, $order, $reverse, $data['limit'] ?? null);
    }

    /**
     * @param string $email
     * @return User
     * @throws UserNotFoundException
     */
    public function getByEmail(string $email): User
    {
        $user = $this->repoRequest->findByFields('email', $email);

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
        $user = $this->repoRequest->getById($id);

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

        return $this->repoCommand->store($data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return User
     * @throws UserNotFoundException
     */
    public function update(int $id, array $data): User
    {
        $user = $this->repoRequest->getById($id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        $this->repoCommand->update($user, $data);

        return $this->repoRequest->getById($id);
    }

    /**
     * @param int $id
     * @return void
     * @throws UserNotFoundException
     */
    public function destroy(int $id): void
    {
        $user = $this->repoRequest->getById($id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        $this->repoCommand->destroy($user);
    }
}
