<?php

namespace App\Http\ViewComposers;

use App;
use App\Repositories\CustomerRepository;
use App\Repositories\LanguageRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LanguageComposer
{
    protected $languageRepository;
    protected $customerRepository;

    public function __construct(LanguageRepository $languageRepository, CustomerRepository $customerRepository)
    {
        $this->languageRepository = $languageRepository;
        $this->customerRepository = $customerRepository;
    }

    public function compose(View $view)
    {
        $languages = $this->languageRepository->findByCondition([config('apps.general.publish')], true, [], ['current', 'DESC']);
        $view->with('languages', $languages);
        $customerId = Auth::guard('customers')->id();
        if (isset($customerId)) {
            $customer = $this->customerRepository->findById($customerId);
            $activeLanguage = $customer->language;
        } else {
            $activeLanguage = App::getLocale() ?? config('app.locale');
        }
        $view->with('activeLanguage', $activeLanguage);
    }
}
