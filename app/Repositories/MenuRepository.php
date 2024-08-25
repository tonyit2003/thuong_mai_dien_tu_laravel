<?php

namespace App\Repositories;

use App\Models\Menu;
use App\Repositories\Interfaces\MenuRepositoryInterface;

/**
 * Class MenuRepository
 * @package App\Repositories
 */
class MenuRepository extends BaseRepository implements MenuRepositoryInterface
{
    protected $model;

    public function __construct(Menu $menu)
    {
        $this->model = $menu;
        parent::__construct($this->model); //truyền model lên lớp cha
    }
}
