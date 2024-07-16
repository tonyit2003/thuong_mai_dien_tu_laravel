<?php

namespace App\Repositories\Interfaces;

/**
 * Interface BaseRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface BaseRepositoryInterface
{
    public function all($relation = []);
    public function findById($modelId, $column, $relation);
    public function findByCondition($condition = []);
    public function pagination($column = ['*'], $condition = [], $join = [], $perpage = 20, $extend = [], $relations = [], $orderBy = ['id', 'DESC'], $rawQuery = []);
    public function create($payload = []);
    public function createPivot($model, $payload = [], $relation = '');
    public function update($id = 0, $payload = []);
    public function updateByWhereIn($whereInField = '', $whereIn = [], $payload = []);
    public function updateByWhere($condition = [], $payload = []);
    public function delete($id = 0);
    public function forceDelete($id = 0);
}
