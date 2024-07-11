<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLanguageRequest;
use App\Http\Requests\UpdateLanguageRequest;
use App\Repositories\LanguageRepository;
use App\Services\LanguageService;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    protected $languageService;
    protected $languageRepository;

    public function __construct(LanguageService $languageService, LanguageRepository $languageRepository)
    {
        $this->languageService = $languageService;
        $this->languageRepository = $languageRepository;
    }

    public function index(Request $request)
    {
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
        $config['seo'] = config('apps.language');

        $template = 'backend.language.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'languages'));
    }

    public function create()
    {
        $config = $this->configData();
        $config['seo'] = config('apps.language');
        $config['method'] = 'create';
        $template = 'backend.language.store';
        return view('backend.dashboard.layout', compact('template', 'config'));
    }

    public function store(StoreLanguageRequest $storeLanguageRequest)
    {
        if ($this->languageService->create($storeLanguageRequest)) {
            flash()->success('Thêm mới bản ghi thành công');
            return redirect()->route('language.index');
        }
        flash()->error('Thêm mới bản ghi không thành công');
        return redirect()->route('language.index');
    }

    public function edit($id)
    {
        $config = $this->configData();
        $language = $this->languageRepository->findById($id);
        $config['seo'] = config('apps.language');
        $config['method'] = 'edit';
        $template = 'backend.language.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'language'));
    }

    public function update($id, UpdateLanguageRequest $updateLanguageRequest)
    {
        if ($this->languageService->update($id, $updateLanguageRequest)) {
            flash()->success('Cập nhật bản ghi thành công');
            return redirect()->route('language.index');
        }
        flash()->error('Cập nhật bản ghi không thành công');
        return redirect()->route('language.index');
    }

    public function delete($id)
    {
        $language = $this->languageRepository->findById($id);
        $config['seo'] = config('apps.language');
        $template = 'backend.language.delete';
        return view('backend.dashboard.layout', compact('template', 'language', 'config'));
    }

    public function destroy($id)
    {
        if ($this->languageService->delete($id)) {
            flash()->success('Xóa bản ghi thành công');
            return redirect()->route('language.index');
        }
        flash()->error('Xóa bản ghi không thành công');
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
}
