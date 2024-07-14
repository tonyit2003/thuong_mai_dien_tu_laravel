<?php

namespace App\Repositories;

use App\Models\Province;
use App\Repositories\Interfaces\ProvinceRepositoryInterface;

/**
 * Class ProvinceRepository
 * @package App\Repositories
 */
class ProvinceRepository extends BaseRepository implements ProvinceRepositoryInterface
{
    protected $model;

    public function __construct(Province $province)
    {
        $this->model = $province;
        parent::__construct($this->model); //truyền model lên lớp cha
    }
}
