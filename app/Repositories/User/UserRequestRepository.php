<?php

namespace App\Repositories\User;

use App\Models\Scopes\Filter\AbstractFilter;
use App\Models\User as Model;
use App\Repositories\CoreRepository;


/**
 * Репозиторий запросов Users
 */
class UserRequestRepository extends CoreRepository {

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return Model::class;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getById(int $id): mixed
    {
        return $this->startConditions()->find($id);
    }

    /**
     * @param string $key
     * @param string $value
     * @return mixed
     */
    public function findByFields(string $key, string $value): mixed
    {
        return $this->startConditions()
            ->where($key, $value)
            ->get();
    }

    /**
     * @param AbstractFilter $filter
     * @param string $order
     * @param string $reverse
     * @param int|null $limit
     * @return mixed
     */
    public function findList(AbstractFilter $filter, string $order, string $reverse, ?int $limit = null): mixed
    {
        $users = Model::query()
            ->filter($filter)
            ->orderBy($order, $reverse);

        return is_null($limit)
            ? $users->get()
            : $users->paginate($limit);
    }
}
