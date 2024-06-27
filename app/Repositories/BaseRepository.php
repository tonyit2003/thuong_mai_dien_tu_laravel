<?php

namespace App\Repositories;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseRepository
 * @package App\Repositories
 */
class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function pagination($column = ['*'], $condition = [], $join = [], $perpage = 20)
    {
        $query = $this->model->select($column)->where($condition);
        if (!empty($join)) {
            $query->join(...$join);
        }
        return $query->paginate($perpage);
    }

    public function create($payload = [])
    {
        return $this->model->create($payload)->fresh();
    }

    public function update($id = 0, $payload = [])
    {
        return $this->findById($id)->update($payload);
    }

    // xóa mềm (thêm cột delete_at trong bảng users và thêm SoftDeletes trong Model\User)
    public function delete($id = 0)
    {
        return $this->findById($id)->delete();
    }

    public function forceDelete($id = 0)
    {
        return $this->findById($id)->forceDelete();
    }

    public function all()
    {
        return $this->model->all();
    }

    // tìm kiếm dựa trên id (id của bảng cần tìm, các cột cần select, các quan hệ: được định nghĩa trong model)
    public function findById($modelId, $column = ['*'], $relation = [])
    {
        return $this->model->select($column)->with($relation)->findOrFail($modelId);
    }
}
