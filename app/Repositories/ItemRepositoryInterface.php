<?php

namespace App\Repositories;

interface ItemRepositoryInterface {

    public function getAll(array $attributes);

    public function findById(int $id);

    public function create(array $attributes);

    public function update(int $id, array $attributes);

    public function delete($id): void;
}
