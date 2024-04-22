<?php

namespace App\Repositories;

use Illuminate\Contracts\Foundation\Application as App;
use Illuminate\Foundation\Application as ContractApp;


/**
 * Абстрактный репозиторий
 */
abstract class CoreRepository {

    protected mixed $model;


    public function __construct()
    {
        $this->model = app($this->getModelClass());
    }

    /**
     * @return mixed
     */
    abstract protected function getModelClass(): mixed;

    /**
     * @return App|ContractApp|mixed
     */
    protected function startConditions(): mixed
    {
        return clone $this->model;
    }
}
