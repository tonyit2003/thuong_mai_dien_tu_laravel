<?php

namespace App\Repositories;

use App\Models\Permission;
use App\Repositories\Interfaces\PermissionRepositoryInterface;

/**
 * Class PermissionRepository
 * @package App\Repositories
 */
class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    protected $model;

    public function __construct(Permission $permission)
    {
        $this->model = $permission;
        parent::__construct($this->model); //truyền model lên lớp cha
    }
}
