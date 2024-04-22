<?php

namespace App\Repositories\User;

use App\Models\User as Model;
use App\Repositories\CoreRepository;


/**
 * Репозиторий команд Users
 */
class UserCommandRepository extends CoreRepository {

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return Model::class;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function store(array $data): mixed
    {
        return Model::create($data);
    }

    /**
     * @param Model $model
     * @param array $data
     * @return void
     */
    public function update(Model $model, array $data): void
    {
        $model->update($data);
    }

    /**
     * @param Model $model
     * @return void
     */
    public function destroy(Model $model): void
    {
        $model->delete();
    }
}
