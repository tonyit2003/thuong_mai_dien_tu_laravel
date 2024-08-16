<?php

namespace App\Repositories;

use App\Models\System;
use App\Repositories\Interfaces\SystemRepositoryInterface;

/**
 * Class SystemRepository
 * @package App\Repositories
 */
class SystemRepository extends BaseRepository implements SystemRepositoryInterface
{
    protected $model;

    public function __construct(System $system)
    {
        $this->model = $system;
        parent::__construct($this->model); //truyền model lên lớp cha
    }
}
