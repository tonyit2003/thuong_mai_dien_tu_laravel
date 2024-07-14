<?php

namespace App\Repositories;

use App\Models\Router;
use App\Repositories\Interfaces\RouterRepositoryInterface;

/**
 * Class RouterRepository
 * @package App\Repositories
 */
class RouterRepository extends BaseRepository implements RouterRepositoryInterface
{
    protected $model;

    public function __construct(Router $router)
    {
        $this->model = $router;
        parent::__construct($this->model); //truyền model lên lớp cha
    }
}
