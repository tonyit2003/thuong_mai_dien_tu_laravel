<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\System;
use App\Repositories\SystemRepository;
use Illuminate\Support\Facades\App;

class FrontendController extends Controller
{
    protected $language;
    protected $system;

    public function __construct()
    {
        $this->setLanguage();
        $this->setSystem();
    }

    public function setLanguage()
    {
        $locale = App::getLocale();
        $language = Language::where('canonical', $locale)->first();
        $this->language = $language->id;
    }

    public function setSystem()
    {
        $this->system = convert_array(System::where('language_id', $this->language)->get(), 'keyword', 'content');;
    }
}
