<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\System;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;

abstract class Controller extends BaseController
{
    protected $language;
    protected $system;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $locale = App::getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->system = convert_array(System::where('language_id', $this->language)->get(), 'keyword', 'content');
            return $next($request);
        });
    }
}
