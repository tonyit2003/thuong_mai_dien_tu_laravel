<?php

namespace App\Repositories;

use App\Models\Review;
use App\Repositories\Interfaces\ReviewRepositoryInterface;

/**
 * Class ReviewRepository
 * @package App\Repositories
 */
class ReviewRepository extends BaseRepository implements ReviewRepositoryInterface
{
    protected $model;

    public function __construct(Review $review)
    {
        $this->model = $review;
        parent::__construct($this->model); //truyền model lên lớp cha
    }
}
