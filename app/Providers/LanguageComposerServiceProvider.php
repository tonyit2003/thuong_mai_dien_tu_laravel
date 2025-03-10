<?php

namespace App\Providers;

use App\Repositories\LanguageRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class LanguageComposerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    // đăng ký một view composer (sẽ được gọi khi view cụ thể được render) cho view backend.dashboard.component.nav.
    public function boot(): void
    {
        View::composer('backend.dashboard.layout', function ($view) {
            $languageRepository = $this->app->make(LanguageRepository::class);
            $allLanguages = $languageRepository->all();

            $currentCanonical = App::getLocale();

            // Chia sẻ dữ liệu language và currentCanonical với view backend.dashboard.component.nav.
            $view->with('allLanguages', $allLanguages);
            $view->with('currentCanonical', $currentCanonical);
        });
    }
}
