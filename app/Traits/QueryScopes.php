<?php

namespace App\Traits;

trait QueryScopes
{
    public function scopeKeyword($query, $keyword, $fieldSearch = [])
    {
        if (isset($keyword) && !empty($keyword)) {
            if (isset($fieldSearch) && count($fieldSearch)) {
                $query->where(function ($query) use ($fieldSearch, $keyword) {
                    foreach ($fieldSearch as $key => $val) {
                        $query->orWhere($val, 'LIKE', '%' . $keyword . '%');
                    }
                });
            } else {
                $query->where('name', 'LIKE', '%' . $keyword . '%');
            }
        }
        return $query;
    }

    public function scopePublish($query, $publish)
    {
        if (isset($publish) && $publish != -1) {
            $query->where('publish', '=', $publish);
        }
        return $query;
    }

    public function scopeCustomWhere($query, $where)
    {
        if (isset($where) && !empty($where)) {
            foreach ($where as $val) {
                $query->where($val[0], $val[1], $val[2]);
            }
        }
        return $query;
    }

    public function scopeCustomWhereRaw($query, $rawQuery)
    {
        // điều kiện truy vấn bằng câu sql
        if (isset($rawQuery) && count($rawQuery)) {
            foreach ($rawQuery as $key => $val) {
                $query->whereRaw($val[0], $val[1]); // $val[0]: câu truy vấn, $val[1]: giá trị tham số trong câu truy vấn
            }
        }
        return $query;
    }

    public function scopeRelationCount($query, $relations)
    {
        // truy vấn bằng quan hệ giữa các model
        if (isset($relations) && !empty($relations)) {
            foreach ($relations as $relation) {
                $query->withCount($relation);
            }
        }
        return $query;
    }

    public function scopeRelation($query, $relations)
    {
        // truy vấn bằng quan hệ giữa các model
        if (isset($relations) && !empty($relations)) {
            foreach ($relations as $relation) {
                $query->with($relation);
            }
        }
        return $query;
    }

    public function scopeCustomJoin($query, $join)
    {
        // kết các bảng lại với nhau
        if (isset($join) && is_array($join) && count($join)) {
            foreach ($join as $key => $val) {
                $query->join($val[0], $val[1], $val[2], $val[3]);
            }
        }
        return $query;
    }

    public function scopeCustomGroupBy($query, $groupBy)
    {
        // nhóm các kết quả truy vấn
        if (isset($groupBy) && !empty($groupBy)) {
            $query->groupBy($groupBy);
        }
        return $query;
    }

    public function scopeCustomOrderBy($query, $orderBy)
    {
        // sắp xếp
        if (isset($orderBy) && !empty($orderBy)) {
            // 1. cột cần xét sắp xếp
            // 2. kiểu sắp sếp (tăng, giảm)
            $query->orderBy($orderBy[0], $orderBy[1]);
        }
        return $query;
    }

    public function scopeCustomDropdownFilter($query, $condition)
    {
        if (isset($condition) && count($condition)) {
            foreach ($condition as $key => $val) {
                if ($val != 'none' && $val != '') {
                    $query->where($key, '=', $val);
                }
            }
        }
        return $query;
    }

    public function scopeCustomCreatedAt($query, $condition)
    {
        if (isset($condition) && $condition != "") {
            $explode = explode('-', $condition);
            $explode = array_map('trim', $explode);
            $startDate = convertDateTime($explode[0], 'Y-m-d 00:00:00', 'd/m/Y');
            $endDate = convertDateTime($explode[1], 'Y-m-d 23:59:59', 'd/m/Y');
            $query->whereDate('created_at', '>=', $startDate);
            $query->whereDate('created_at', '<=', $endDate);
        }
        return $query;
    }
}
