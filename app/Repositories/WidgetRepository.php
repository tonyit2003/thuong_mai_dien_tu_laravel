<?php

namespace App\Repositories;

use App\Models\Widget;
use App\Repositories\Interfaces\WidgetRepositoryInterface;

/**
 * Class WidgetRepository
 * @package App\Repositories
 */
class WidgetRepository extends BaseRepository implements WidgetRepositoryInterface
{
    protected $model;

    public function __construct(Widget $widget)
    {
        $this->model = $widget;
        parent::__construct($this->model);
    }

    public function getWidgetByWhereIn($whereIn = [], $whereInField = 'keyword')
    {
        return $this->model->where([config('apps.general.publish')])
            ->whereIn($whereInField, $whereIn)
            // FIELD() => sắp xếp các giá trị dựa trên một thứ tự cụ thể do người dùng chỉ định.
            // (FIELD('keyword', 'keyword1', 'keyword2', 'keyword3')) => các bản ghi sẽ được sắp xếp theo thứ tự chứa các keyword
            // implode() => biến mảng $keyword thành một chuỗi giá trị tách biệt bởi dấu phẩy (,)
            ->orderByRaw("FIELD(keyword, '" . implode("','", $whereIn) . "')")
            ->get();
    }
}
