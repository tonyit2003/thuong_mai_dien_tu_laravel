<?php

namespace App\Repositories\Interfaces;

/**
 * Interface BaseRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface BaseRepositoryInterface
{
    public function all();
    public function findById($modelId, $column, $relation);
    public function pagination($column = ['*'], $condition = [], $join = [], $perpage = 20, $extend = []);
    public function create($payload = []);
    public function update($id = 0, $payload = []);
    public function updateByWhereIn($whereInField = '', $whereIn = [], $payload = []);
    public function delete($id = 0);
    public function forceDelete($id = 0);
}
