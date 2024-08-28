<?php

namespace App\Repositories;

use App\Models\Slide;
use App\Repositories\Interfaces\SlideRepositoryInterface;

/**
 * Class SlideRepository
 * @package App\Repositories
 */
class SlideRepository extends BaseRepository implements SlideRepositoryInterface
{
    protected $model;

    public function __construct(Slide $slide)
    {
        $this->model = $slide;
        parent::__construct($this->model); //truyền model lên lớp cha
    }
}
