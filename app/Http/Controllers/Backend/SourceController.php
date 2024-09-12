<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Source\StoreSourceRequest;
use App\Http\Requests\Source\UpdateSourceRequest;
use App\Models\Language;
use App\Repositories\LanguageRepository;
use App\Repositories\SourceRepository;
use App\Services\SourceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

class SourceController extends Controller
{
    protected $sourceService;
    protected $sourceRepository;
    protected $languageRepository;

    public function __construct(SourceService $sourceService, SourceRepository $sourceRepository, LanguageRepository $languageRepository)
    {
        $this->sourceService = $sourceService;
        $this->sourceRepository = $sourceRepository;
        $this->languageRepository = $languageRepository;
        $this->middleware(function ($request, $next) {
            $locale = App::getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        Gate::authorize('modules', 'source.index');
        $sources = $this->sourceService->paginate($request);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'Source'
        ];
        $config['seo'] = __('source');

        $template = 'backend.source.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'sources'));
    }

    public function create()
    {
        Gate::authorize('modules', 'source.create');
        $config = $this->configData();
        $config['seo'] = __('source');
        $config['method'] = 'create';
        $template = 'backend.source.store';
        return view('backend.dashboard.layout', compact('template', 'config'));
    }

    public function store(StoreSourceRequest $storeSourceRequest)
    {
        if ($this->sourceService->create($storeSourceRequest)) {
            flash()->success(__('toast.store_success'));
            return redirect()->route('source.index');
        }
        flash()->error(__('toast.store_failed'));
        return redirect()->route('source.index');
    }

    public function edit($id)
    {
        Gate::authorize('modules', 'source.update');
        $source = $this->sourceRepository->findById($id);
        $config = $this->configData();
        $config['seo'] = __('source');
        $config['method'] = 'edit';
        $template = 'backend.source.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'source'));
    }

    public function update($id, UpdateSourceRequest $updateSourceRequest)
    {
        if ($this->sourceService->update($id, $updateSourceRequest)) {
            flash()->success(__('toast.update_success'));
            return redirect()->route('source.index');
        }
        flash()->error(__('toast.update_failed'));
        return redirect()->route('source.index');
    }

    public function delete($id)
    {
        Gate::authorize('modules', 'source.destroy');
        $source = $this->sourceRepository->findById($id);
        $config['seo'] = __('source');
        $template = 'backend.source.delete';
        return view('backend.dashboard.layout', compact('template', 'source', 'config'));
    }

    public function destroy($id)
    {
        if ($this->sourceService->delete($id)) {
            flash()->success(__('toast.destroy_success'));
            return redirect()->route('source.index');
        }
        flash()->error(__('toast.destroy_failed'));
        return redirect()->route('source.index');
    }

    private function configData()
    {
        return [
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/plugins/ckeditor/ckeditor.js',
            ]
        ];
    }
}
