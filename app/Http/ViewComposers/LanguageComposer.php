<?php

namespace App\Http\ViewComposers;

use App\Repositories\LanguageRepository;
use Illuminate\View\View;

class LanguageComposer
{
    protected $languageRepository;

    public function __construct(LanguageRepository $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }

    public function compose(View $view)
    {
        $languages = $this->languageRepository->findByCondition([config('apps.general.publish')], true, [], ['current', 'DESC']);
        $view->with('languages', $languages);
    }
}
