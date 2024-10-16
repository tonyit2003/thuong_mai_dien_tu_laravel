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
        dd(123);
    }
}
