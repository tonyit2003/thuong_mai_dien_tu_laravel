<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\SlideEnum;
use App\Enums\WidgetEnum;
use App\Http\Controllers\FrontendController;

class CartController extends FrontendController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function checkout()
    {
        $language = $this->language;
        $config = $this->config();
        $system = $this->system;
        $seo = [
            'meta_title' => __('info.pay_meta_title'),
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => write_url('pay', true, true)
        ];
        return view('frontend.cart.index', compact('language', 'seo', 'system', 'config'));
    }

    private function config()
    {
        return [];
    }
}
