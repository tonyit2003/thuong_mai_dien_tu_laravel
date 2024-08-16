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

    public function all($relation = [])
    {
        return $this->model->with($relation)->get();
    }

    public function pagination($column = ['*'], $condition = [], $join = [], $perpage = 20, $extend = [], $relations = [], $orderBy = ['id', 'DESC'], $rawQuery = [])
    {
        $query = $this->model->select($column);
        // gọi các scope trong model (được khai báo trong trait) để truy vấn (laravel tự động điền $query vào tham số đầu tiên)
        return $query->keyword($condition['keyword'] ?? null)
            ->publish($condition['publish'] ?? -1)
            ->customWhere($condition['where'] ?? null)
            ->customWhereRaw($rawQuery['whereRaw'] ?? null)
            ->relationCount($relations ?? null)
            ->relation($relations ?? null)
            ->customJoin($join ?? null)
            ->customGroupBy($extend['groupBy'] ?? null)
            ->customOrderBy($orderBy ?? null)
            // withQueryString: giữ lại cái điều kiện trên url (perpage=20&user_catalogue_id=0&keyword=Khalil&search=search&page=2)
            // withPath: đường dẫn đến các điều kiện đó (http://localhost/thuongmaidientu/public/user/index)
            // 2 phương thức trên cần gọi sau khi gọi phương thức paginate() để đảm bảo rằng chúng được áp dụng đúng cách vào đối tượng phân trang
            ->paginate($perpage)->withQueryString()->withPath(env('APP_URL') . $extend['path']);
    }

    public function create($payload = [])
    {
        // create => chèn một bản ghi trực tiếp vào cơ sở dữ liệu
        // fresh() => tải lại đối tượng model từ cơ sở dữ liệu sau khi nó đã được tạo hoặc cập nhật => đảm bảo rằng đối tượng model đang làm việc có chứa dữ liệu mới nhất từ cơ sở dữ liệu
        return $this->model->create($payload)->fresh();
    }

    public function createBatch($payload = [])
    {
        // insert: chèn một hoặc nhiều bản ghi trực tiếp vào cơ sở dữ liệu
        return $this->model->insert($payload);
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

    // cập nhật các bản ghi trong cơ sở dữ liệu dựa trên điều kiện được chỉ định
    public function updateByWhere($condition = [], $payload = [])
    {
        // Tạo một instance của query builder cho model tương ứng
        $query = $this->model->newQuery();
        // Thêm diều kiện vào câu truy vấn
        foreach ($condition as $key => $val) {
            $query->where($val[0], $val[1], $val[2]);
        }
        // gọi update trên câu truy vấn có điều kiện để thực hiện cập nhật
        return $query->update($payload);
    }

    public function updateOrInsert($payload = [], $condition = [])
    {
        // updateOrInsert() => kiểm tra xem bản ghi có tồn tại trong cơ sở dữ liệu hay không ($condition). Nếu có, nó sẽ cập nhật bản ghi đó; nếu không, nó sẽ chèn một bản ghi mới.
        // $condition => Điều kiện tìm kiếm
        // $payload => Giá trị để cập nhật hoặc chèn
        // Chỉ có thể chèn hoặc cập nhật một bản ghi duy nhất.
        return $this->model->updateOrInsert($condition, $payload);
    }

    // xóa mềm (thêm cột delete_at trong bảng users và thêm SoftDeletes trong Model\User)
    public function delete($id = 0)
    {
        return $this->findById($id)->delete();
    }

    // Xóa dữ liệu khỏi csdl
    public function forceDelete($id = 0)
    {
        return $this->findById($id)->forceDelete();
    }

    // Xóa dữ liệu khỏi csdl theo điểu kiện
    public function forceDeleteByCondition($condition = [])
    {
        // Khởi tạo một đối tượng truy vấn mới từ model
        $query = $this->model->newQuery();

        // Thêm điều kiện vào truy vấn
        foreach ($condition as $key => $val) {
            $query->where($val[0], $val[1], $val[2]);
        }

        return $query->forceDelete();
    }

    // tìm kiếm dựa trên id (id của bảng cần tìm, các cột cần select, các quan hệ: được định nghĩa trong model)
    public function findById($modelId, $column = ['*'], $relation = [])
    {
        return $this->model->select($column)->with($relation)->findOrFail($modelId);
    }

    // tìm kiếm một bản ghi từ cơ sở dữ liệu dựa trên các điều kiện
    public function findByCondition($condition = [], $flag = false)
    {
        // Khởi tạo một đối tượng truy vấn mới từ model
        $query = $this->model->newQuery();

        // Thêm điều kiện vào truy vấn
        foreach ($condition as $key => $val) {
            $query->where($val[0], $val[1], $val[2]);
        }

        // Trả về bản ghi đầu tiên tìm được
        return $flag == false ? $query->first() : $query->get();
    }
}
