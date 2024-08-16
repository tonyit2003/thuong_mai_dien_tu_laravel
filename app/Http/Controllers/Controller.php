<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;

abstract class Controller extends BaseController
{
    protected $language;

    public function __construct()
    {
        $this->language = App::getLocale();
    }
}
