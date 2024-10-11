<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Repositories\RouterRepository;
use Illuminate\Http\Request;

class RouterController extends FrontendController
{
    protected $routerRepository;
    protected $router;

    public function __construct(RouterRepository $routerRepository)
    {
        parent::__construct();
        $this->routerRepository = $routerRepository;
    }

    public function index($canonical = '', Request $request)
    {
        $this->getRouter($canonical);
        if (isset($this->router) && !empty($this->router)) {
            $method = 'index';
            // app(): lấy một đối tượng của một lớp cụ thể
            // đoạn lệnh sau sẽ trả về 1 đoạn html và dùng echo để in nó ra
            echo app($this->router->controllers)->{$method}($this->router->module_id, $request);
        }
    }

    public function page($canonical = '', $page = 1, Request $request)
    {
        $page = isset($page) ? $page : 1;
        $this->getRouter($canonical);
        if (isset($this->router) && !empty($this->router)) {
            $method = 'index';
            // app(): lấy một đối tượng của một lớp cụ thể
            // đoạn lệnh sau sẽ trả về 1 đoạn html và dùng echo để in nó ra
            echo app($this->router->controllers)->{$method}($this->router->module_id, $request, $page);
        }
    }

    public function getProduct($canonical = '', $variantId = 0, Request $request)
    {
        $this->getRouter($canonical);
        if (isset($this->router) && !empty($this->router)) {
            $method = 'index';
            // app(): lấy một đối tượng của một lớp cụ thể
            // đoạn lệnh sau sẽ trả về 1 đoạn html và dùng echo để in nó ra
            echo app($this->router->controllers)->{$method}($this->router->module_id, $variantId, $request);
        }
    }

    private function getRouter($canonical)
    {
        $this->router = $this->routerRepository->findByCondition(
            [
                ['canonical', '=', $canonical],
                ['language_id', '=', $this->language]
            ]
        );
    }
}
