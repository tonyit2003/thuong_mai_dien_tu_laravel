<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Repositories\SystemRepository;

class HomeController extends FrontendController
{
    protected $systemRepository;

    public function __construct(SystemRepository $systemRepository)
    {
        // gọi hàm khởi tạo (constructor) của lớp cha
        parent::__construct();
        $this->systemRepository = $systemRepository;
    }

    public function index()
    {
        $config = $this->config();
        return view('frontend.homepage.home.index', compact('config'));
    }

    private function config()
    {
        return [];
    }
}
