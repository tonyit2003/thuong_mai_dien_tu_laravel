<?php

namespace App\Repositories;

use App\Models\Promotion;
use App\Repositories\Interfaces\PromotionRepositoryInterface;

/**
 * Class PromotionsRepository
 * @package App\Repositories
 */
class PromotionRepository extends BaseRepository implements PromotionRepositoryInterface
{
    protected $model;

    public function __construct(Promotion $promotion)
    {
        $this->model = $promotion;
        parent::__construct($this->model);
    }
}
