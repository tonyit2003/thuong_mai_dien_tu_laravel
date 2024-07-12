<?php

namespace App\Repositories;

use App\Models\PostCatalogue;
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

    public function pagination($column = ['*'], $condition = [], $join = [], $perpage = 20, $extend = [], $relations = [], $orderBy = ['id', 'DESC'], $rawQuery = [])
    {
        $query = $this->model->select($column)->where(function ($query) use ($condition) {
            if (isset($condition['publish']) && $condition['publish'] != -1) {
                $query->where('publish', '=', $condition['publish']);
            }

            if (isset($condition['keyword']) && !empty($condition['keyword'])) {
                $query->where('name', 'LIKE', '%' . $condition['keyword'] . '%');
            }

            if (isset($condition['where']) && !empty($condition['where'])) {
                foreach ($condition['where'] as $val) {
                    $query->where($val[0], $val[1], $val[2]);
                }
            }
        });

        // thêm điều kiện truy vấn bằng câu sql
        if (isset($rawQuery['whereRaw']) && count($rawQuery['whereRaw'])) {
            foreach ($rawQuery['whereRaw'] as $key => $val) {
                $query->whereRaw($val[0], $val[1]); // $val[0]: câu truy vấn, $val[1]: giá trị tham số trong câu truy vấn
            }
        }

        // truy vấn bằng quan hệ giữa các model
        if (isset($relations) && !empty($relations)) {
            foreach ($relations as $relation) {
                $query->withCount($relation);
                $query->with($relation);
            }
        }

        // kết các bảng lại với nhau
        if (isset($join) && is_array($join) && count($join)) {
            foreach ($join as $key => $val) {
                $query->join($val[0], $val[1], $val[2], $val[3]);
            }
        }

        // nhóm các kết quả truy vấn
        if (isset($extend['groupBy']) && !empty($extend['groupBy'])) {
            $query->groupBy($extend['groupBy']);
        }

        // sắp xếp
        if (isset($orderBy) && !empty($orderBy)) {
            // 1. cột cần xét sắp xếp
            // 2. kiểu sắp sếp (tăng, giảm)
            $query->orderBy($orderBy[0], $orderBy[1]);
        }

        // withQueryString: giữ lại cái điều kiện trên url (perpage=20&user_catalogue_id=0&keyword=Khalil&search=search&page=2)
        // withPath: đường dẫn đến các điều kiện đó (http://localhost/thuongmaidientu/public/user/index)
        return $query->paginate($perpage)->withQueryString()->withPath(env('APP_URL') . $extend['path']);
    }

    public function create($payload = [])
    {
        return $this->model->create($payload)->fresh();
    }

    // thêm một bản ghi mới vào bảng trung gian của mối quan hệ "many-to-many" giữa hai model
    public function createPivot($model, $payload = [], $relation = '')
    {
        // $relation: tên phương thức đã được định nghĩa trong model để thiết lập mối quan hệ "many-to-many" giữa model hiện tại và model khác.
        // {$relation} => sử dụng giá trị của $relation để tham chiếu đến thuộc tính hoặc phương thức tương ứng của $model.
        // attach:  thêm một bản ghi mới vào bảng trung gian (khóa chính của model hiện tại, dữ liệu sẽ được lưu vào các cột trong bảng trung gian)
        return $model->{$relation}()->attach($model->id, $payload);
    }

    public function update($id = 0, $payload = [])
    {
        return $this->findById($id)->update($payload);
    }

    public function updateByWhereIn($whereInField = '', $whereIn = [], $payload = [])
    {
        // whereIn($whereInField, $whereIn) chọn các bản ghi mà trường $whereInField có giá trị thuộc mảng $whereIn.
        // vd: chọn các bản ghi có 'id' thuộc mảng [1, 2, 3] để update
        return $this->model->whereIn($whereInField, $whereIn)->update($payload);
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
