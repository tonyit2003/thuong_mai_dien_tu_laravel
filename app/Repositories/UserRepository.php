<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

/**
 * Class UserRepository
 * @package App\Repositories
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
        parent::__construct($this->model); //truyền model lên lớp cha
    }

    public function pagination($column = ['*'], $condition = [], $join = [], $perpage = 20, $extend = [], $relations = [], $orderBy = ['id', 'DESC'], $rawQuery = [])
    {
        $query = $this->model->select($column)->where(function ($query) use ($condition) {
            if (isset($condition['publish']) && $condition['publish'] != -1) {
                $query->where('publish', '=', $condition['publish']);
            }

            // nhóm các điều kiến orWhere và where lại với nhau trong $query->where(function ($query) use ($condition) {} () => tạo câu truy vấn đúng
            // SELECT * FROM users
            // WHERE publish = 1
            // AND (
            //     name LIKE '%keyword%'
            //     OR email LIKE '%keyword%'
            //     OR address LIKE '%keyword%'
            //     OR phone LIKE '%keyword%'
            // )
            if (isset($condition['keyword']) && !empty($condition['keyword'])) {
                $query->where(function ($query) use ($condition) {
                    $query->where('name', 'LIKE', '%' . $condition['keyword'] . '%')
                        ->orWhere('email', 'LIKE', '%' . $condition['keyword'] . '%')
                        ->orWhere('address', 'LIKE', '%' . $condition['keyword'] . '%')
                        ->orWhere('phone', 'LIKE', '%' . $condition['keyword'] . '%');
                });
            }
            // with: lấy các bản ghi từ bảng user_catalogues (tải trước dữ liệu => lấy dữ liệu nhanh hơn)
            // user_catalogues: Tên quan hệ trong model
        })->with('user_catalogues');

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
}
