<?php

namespace App\Http\ViewComposers;

use App\Models\Language;
use App\Repositories\SystemRepository;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;

class SystemComposer
{
    protected $systemRepository;
    protected $language;

    public function __construct(SystemRepository $systemRepository)
    {
        $locale = App::getLocale();
        $language = Language::where('canonical', $locale)->first();
        $this->language = $language->id;
        $this->systemRepository = $systemRepository;
    }

    public function compose(View $view)
    {
        $systems = $this->systemRepository->findByCondition([
            ['language_id', '=', $this->language]
        ], true);
        $systemArray = convert_array($systems, 'keyword', 'content');
        // dd($systemArray);
        $view->with('system', $systemArray);
    }
}
