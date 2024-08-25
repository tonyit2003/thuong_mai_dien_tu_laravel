<?php

namespace App\Repositories;

use App\Models\MenuCatalogue;
use App\Repositories\Interfaces\MenuCatalogueRepositoryInterface;

/**
 * Class MenuCatalogueRepository
 * @package App\Repositories
 */
class MenuCatalogueRepository extends BaseRepository implements MenuCatalogueRepositoryInterface
{
    protected $model;

    public function __construct(MenuCatalogue $menuCatalogue)
    {
        $this->model = $menuCatalogue;
        parent::__construct($this->model); //truyền model lên lớp cha
    }
}
