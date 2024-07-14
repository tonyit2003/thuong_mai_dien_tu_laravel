<?php

namespace App\Traits;

trait QueryScopes
{
    public function scopeKeyword($query, $keyword)
    {
        if (isset($keyword) && !empty($keyword)) {
            $query->where('name', 'LIKE', '%' . $keyword . '%');
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
}