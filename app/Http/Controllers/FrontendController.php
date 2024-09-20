<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Support\Facades\App;

class FrontendController extends Controller
{
    protected $language;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $locale = App::getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }
}
