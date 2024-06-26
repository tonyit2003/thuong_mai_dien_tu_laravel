<?php

namespace App\Repositories;

use App\Models\District;
use App\Repositories\Interfaces\DistrictRepositoryInterface;

/**
 * Class DistrictRepository
 * @package App\Repositories
 */
class DistrictRepository extends BaseRepository implements DistrictRepositoryInterface
{
    protected $model;

    public function __construct(District $district)
    {
        $this->model = $district;
    }

    public function fileDistrictsByProvinceId(int $province_id = 0)
    {
        return $this->model->where('province_code', '=', $province_id)->get();
    }
}
