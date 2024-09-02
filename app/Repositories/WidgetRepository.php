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
}
