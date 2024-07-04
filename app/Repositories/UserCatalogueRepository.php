<?php

namespace App\Repositories;

use App\Models\UserCatalogue;
use App\Repositories\Interfaces\UserCatalogueRepositoryInterface;

/**
 * Class UserCatalogueRepository
 * @package App\Repositories
 */
class UserCatalogueRepository extends BaseRepository implements UserCatalogueRepositoryInterface
{
    protected $model;

    public function __construct(UserCatalogue $userCatalogue)
    {
        $this->model = $userCatalogue;
    }

    public function pagination($column = ['*'], $condition = [], $join = [], $perpage = 20, $extend = [], $relations = [])
    {
        $query = $this->model->select($column)->where(function ($query) use ($condition) {
            if (isset($condition['publish']) && $condition['publish'] != -1) {
                $query->where('publish', '=', $condition['publish']);
            }

            // truy vấn dựa trên dữ liệu đã lọc trước đó
            if (isset($condition['keyword']) && !empty($condition['keyword'])) {
                $query->where(function ($query) use ($condition) {
                    $query->where('name', 'LIKE', '%' . $condition['keyword'] . '%')
                        ->orWhere('description', 'LIKE', '%' . $condition['keyword'] . '%');
                });
            }
        });

        if (!empty($join)) {
            $query->join(...$join);
        }

        if (isset($relations) && !empty($relations)) {
            foreach ($relations as $relation) {
                $query->withCount($relation);
            }
        }

        // withQueryString: giữ lại cái điều kiện trên url (perpage=20&user_catalogue_id=0&keyword=Khalil&search=search&page=2)
        // withPath: đường dẫn đến các điều kiện đó (http://localhost/thuongmaidientu/public/user/index)
        return $query->paginate($perpage)->withQueryString()->withPath(env('APP_URL') . $extend['path']);
    }
}
