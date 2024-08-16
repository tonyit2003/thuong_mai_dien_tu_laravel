<?php

namespace App\Http\Controllers\Backend;

use App\Classes\System;
use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Repositories\SystemRepository;
use App\Services\SystemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SystemController extends Controller
{
    protected $systemLibrary;
    protected $systemService;
    protected $systemRepository;

    public function __construct(System $systemLibrary, SystemService $systemService, SystemRepository $systemRepository)
    {
        $this->middleware(function ($request, $next) {
            $locale = App::getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
        $this->systemLibrary = $systemLibrary;
        $this->systemService = $systemService;
        $this->systemRepository = $systemRepository;
    }

    public function index()
    {
        $systemConfig = $this->systemLibrary->config();
        $condition = [
            ['language_id', '=', $this->language]
        ];
        $systems = convert_array($this->systemRepository->findByCondition($condition, true), "keyword", "content");
        $config = $this->config();
        $config['seo'] = __('system');
        $languageCurrent = $this->language;
        $template = 'backend.system.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'systemConfig', 'systems', 'languageCurrent'));
    }

    public function store(Request $request)
    {
        if ($this->systemService->save($request, $this->language)) {
            flash()->success(__('toast.update_success'));
            return redirect()->route('system.index');
        }
        flash()->error(__('toast.update_failed'));
        return redirect()->route('system.index');
    }

    public function translate($languageId = 0)
    {
        $systemConfig = $this->systemLibrary->config();
        $condition = [
            ['language_id', '=', $languageId]
        ];
        $systems = convert_array($this->systemRepository->findByCondition($condition, true), "keyword", "content");
        $config = $this->config();
        $config['seo'] = __('system');
        $config['method'] = 'translate';
        $template = 'backend.system.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'systemConfig', 'systems', 'languageId'));
    }

    public function saveTranslate(Request $request, $languageId)
    {
        if ($this->systemService->save($request, $languageId)) {
            flash()->success(__('toast.update_success'));
            return redirect()->route('system.translate', ['languageId' => $languageId]);
        }
        flash()->error(__('toast.update_failed'));
        return redirect()->route('system.translate', ['languageId' => $languageId]);
    }

    private function config()
    {
        return [
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js'
            ]
        ];
    }
}
