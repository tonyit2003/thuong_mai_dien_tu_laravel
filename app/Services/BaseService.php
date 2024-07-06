<?php

namespace App\Services;

use App\Services\Interfaces\BaseServiceInterface;

/**
 * Class BaseService
 * @package App\Services
 */
class BaseService implements BaseServiceInterface
{
    protected $model;

    public function __construct()
    {
    }

    public function currentLanguage()
    {
        return 1;
    }
}
