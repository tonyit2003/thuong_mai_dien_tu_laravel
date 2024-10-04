<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Repositories\RouterRepository;

class RouterController extends FrontendController
{
    protected $routerRepository;

    public function __construct(RouterRepository $routerRepository)
    {
        parent::__construct();
        $this->routerRepository = $routerRepository;
    }

    public function index($canonical = '')
    {
        $router = $this->routerRepository->findByCondition(
            [
                ['canonical', '=', $canonical],
                ['language_id', '=', $this->language]
            ]
        );
        if (isset($router) && !empty($router)) {
            $method = 'index';
            // app(): lấy một đối tượng của một lớp cụ thể
            // đoạn lệnh sau sẽ trả về 1 đoạn html và dùng echo để in nó ra
            echo app($router->controllers)->{$method}($router->module_id, $this->language);
        }
    }
}
