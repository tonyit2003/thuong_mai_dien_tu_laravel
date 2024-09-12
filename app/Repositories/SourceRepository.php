<?php

namespace App\Repositories;

use App\Models\AttributeCatalogue;
use App\Models\Source;
use App\Repositories\Interfaces\SourceRepositoryInterface;

/**
 * Class SourceRepository
 * @package App\Repositories
 */
class SourceRepository extends BaseRepository implements SourceRepositoryInterface
{
    protected $model;

    public function __construct(Source $source)
    {
        $this->model = $source;
        parent::__construct($this->model);
    }
}
