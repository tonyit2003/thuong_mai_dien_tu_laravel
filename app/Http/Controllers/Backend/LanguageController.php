<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLanguageRequest;
use App\Http\Requests\TranslateLanguageRequest;
use App\Http\Requests\TranslateRequest;
use App\Http\Requests\UpdateLanguageRequest;
use App\Repositories\LanguageRepository;
use App\Services\LanguageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

class LanguageController extends Controller
{
    protected $languageService;
    protected $languageRepository;

    public function __construct(LanguageService $languageService, LanguageRepository $languageRepository)
    {
        $this->languageService = $languageService;
        $this->languageRepository = $languageRepository;
        parent::__construct();
    }

    public function index(Request $request)
    {
        Gate::authorize('modules', 'language.index');
        $languages = $this->languageService->paginate($request);

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'Language'
        ];
        $config['seo'] = __('language');

        $template = 'backend.language.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'languages'));
    }

    public function create()
    {
        Gate::authorize('modules', 'language.create');
        $config = $this->configData();
        $config['seo'] = __('language');
        $config['method'] = 'create';
        $template = 'backend.language.store';
        return view('backend.dashboard.layout', compact('template', 'config'));
    }

    public function store(StoreLanguageRequest $storeLanguageRequest)
    {
        if ($this->languageService->create($storeLanguageRequest)) {
            flash()->success(__('toast.store_success'));
            return redirect()->route('language.index');
        }
        flash()->error(__('toast.store_failed'));
        return redirect()->route('language.index');
    }

    public function edit($id)
    {
        Gate::authorize('modules', 'language.update');
        $config = $this->configData();
        $language = $this->languageRepository->findById($id);
        $config['seo'] = __('language');
        $config['method'] = 'edit';
        $template = 'backend.language.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'language'));
    }

    public function update($id, UpdateLanguageRequest $updateLanguageRequest)
    {
        if ($this->languageService->update($id, $updateLanguageRequest)) {
            flash()->success(__('toast.update_success'));
            return redirect()->route('language.index');
        }
        flash()->error(__('toast.update_failed'));
        return redirect()->route('language.index');
    }

    public function delete($id)
    {
        Gate::authorize('modules', 'language.destroy');
        $language = $this->languageRepository->findById($id);
        $config['seo'] = __('language');
        $template = 'backend.language.delete';
        return view('backend.dashboard.layout', compact('template', 'language', 'config'));
    }

    public function destroy($id)
    {
        if ($this->languageService->delete($id)) {
            flash()->success(__('toast.destroy_success'));
            return redirect()->route('language.index');
        }
        flash()->error(__('toast.destroy_failed'));
        return redirect()->route('language.index');
    }

    private function configData()
    {
        return [
            'js' => [
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js'
            ]
        ];
    }

    public function switchBackendLanguage($id)
    {
        $this->languageService->switch($id);
        // chuyển hướng đến vị trí trước đó
        return redirect()->back();
    }

    // nhận các giá trị trên url theo thứ tự
    public function translate($id = 0, $languageId = 0, $model = '')
    {
        Gate::authorize('modules', 'language.translate');
        $repositoryInstance = $this->repositoryInstance($model);
        $languageInstance = $this->repositoryInstance('Language');
        $currentLanguage = $languageInstance->findByCondition([['canonical', '=', App::getLocale()]]);
        $method = 'get' . $model . 'ById';
        $object = $repositoryInstance->{$method}($id, $currentLanguage->id);
        $objectTranslate = $repositoryInstance->{$method}($id, $languageId);
        $config = [
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/seo.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ]
        ];
        $config['seo'] = __('language');
        $option = [
            'id' => $id,
            'languageId' => $languageId,
            'model' => $model
        ];
        $template = 'backend.language.translate';
        return view('backend.dashboard.layout', compact('template', 'config', 'object', 'objectTranslate', 'option'));
    }

    public function storeTranslate(TranslateRequest $translateRequest)
    {
        if ($this->languageService->saveTranslate($translateRequest)) {
            flash()->success(__('toast.update_success'));
            return redirect()->back();
        }
        flash()->error(__('toast.update_failed'));
        return redirect()->back();
    }

    // nhận các giá trị trên url theo thứ tự
    public function translateLanguage($id = 0, $languageId = 0, $model = '')
    {
        Gate::authorize('modules', 'language.translate');
        $repositoryInstance = $this->repositoryInstance($model);
        $languageInstance = $this->repositoryInstance('Language');
        $currentLanguage = $languageInstance->findByCondition([['canonical', '=', App::getLocale()]]);
        $method = 'get' . $model . 'ById';
        $object = $repositoryInstance->{$method}($id, $currentLanguage->id);
        $objectTranslate = $repositoryInstance->{$method}($id, $languageId);
        $config = [
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/seo.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ]
        ];
        $config['seo'] = __('language');
        $option = [
            'id' => $id,
            'languageId' => $languageId,
            'model' => $model
        ];
        $template = 'backend.language.translateLanguage';
        return view('backend.dashboard.layout', compact('template', 'config', 'object', 'objectTranslate', 'option'));
    }

    public function storetranslateLanguage(TranslateLanguageRequest $translateRequest)
    {
        if ($this->languageService->saveTranslateLanguage($translateRequest)) {
            flash()->success(__('toast.update_success'));
            return redirect()->back();
        }
        flash()->error(__('toast.update_failed'));
    }

    public function switchFrontendLanguage($id)
    {
        $this->languageService->switchBackend($id);
        // chuyển hướng đến vị trí trước đó
        return redirect()->back();
    }

    private function repositoryInstance($model)
    {
        $repositoryNamespace = '\App\Repositories\\' . ucfirst($model) . 'Repository';
        if (class_exists($repositoryNamespace)) {
            $repositoryInstance = app($repositoryNamespace);
        }
        return $repositoryInstance ?? null;
    }
}
