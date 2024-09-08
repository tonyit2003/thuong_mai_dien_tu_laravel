<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePromotionRequest;
use App\Http\Requests\UpdatePromotionRequest;
use App\Models\Language;
use App\Repositories\LanguageRepository;
use App\Repositories\PromotionRepository;
use App\Services\PromotionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

class PromotionController extends Controller
{
    protected $promotionService;
    protected $promotionRepository;
    protected $languageRepository;

    public function __construct(PromotionService $promotionService, PromotionRepository $promotionRepository, LanguageRepository $languageRepository)
    {
        $this->promotionService = $promotionService;
        $this->promotionRepository = $promotionRepository;
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
        Gate::authorize('modules', 'promotion.index');
        $promotions = $this->promotionService->paginate($request);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'Promotion'
        ];
        $config['seo'] = __('promotion');

        $template = 'backend.promotion.promotion.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'promotions'));
    }

    public function create()
    {
        Gate::authorize('modules', 'promotion.create');
        $config = $this->configData();
        $config['seo'] = __('promotion');
        $config['method'] = 'create';
        $template = 'backend.promotion.promotion.store';
        return view('backend.dashboard.layout', compact('template', 'config'));
    }

    public function store(StorePromotionRequest $storePromotionRequest)
    {
        if ($this->promotionService->create($storePromotionRequest, $this->language)) {
            flash()->success(__('toast.store_success'));
            return redirect()->route('promotion.index');
        }
        flash()->error(__('toast.store_failed'));
        return redirect()->route('promotion.index');
    }

    public function edit($id)
    {
        Gate::authorize('modules', 'promotion.update');
        $promotion = $this->promotionRepository->findById($id);
        $promotion->description = $promotion->description[$this->language];
        $modelClass = loadClass($promotion->model);
        $fields = ['id', 'name.languages', 'image'];
        $promotionItem = $modelClass->findByCondition(...array_values($this->menuItemArgument($promotion->model_id)));
        $modelItem = convertArrayByKey($promotionItem, $fields);
        $album = $promotion->album;
        $config = $this->configData();
        $config['seo'] = __('promotion');
        $config['method'] = 'edit';
        $template = 'backend.promotion.promotion.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'promotion', 'album', 'modelItem'));
    }

    public function update($id, UpdatePromotionRequest $updatePromotionRequest)
    {
        if ($this->promotionService->update($id, $updatePromotionRequest, $this->language)) {
            flash()->success(__('toast.update_success'));
            return redirect()->route('promotion.index');
        }
        flash()->error(__('toast.update_failed'));
        return redirect()->route('promotion.index');
    }

    public function delete($id)
    {
        Gate::authorize('modules', 'promotion.destroy');
        $promotion = $this->promotionRepository->findById($id);
        $config['seo'] = __('promotion');
        $template = 'backend.promotion.promotion.delete';
        return view('backend.dashboard.layout', compact('template', 'promotion', 'config'));
    }

    public function destroy($id)
    {
        if ($this->promotionService->delete($id)) {
            flash()->success(__('toast.destroy_success'));
            return redirect()->route('promotion.index');
        }
        flash()->error(__('toast.destroy_failed'));
        return redirect()->route('promotion.index');
    }

    public function translate($languageId, $promotionId)
    {
        $translate = $this->languageRepository->findById($languageId);
        $promotion = $this->promotionRepository->findById($promotionId);
        $promotionDescription = $promotion->description;
        $promotion->description = $promotionDescription[$this->language];
        $promotion->translateDescription = $promotionDescription[$languageId] ?? null;
        $config = $this->configData();
        $config['seo'] = __('promotion', ['language' => lcfirst($translate->name)]);
        $config['method'] = 'translate';
        $template = 'backend.promotion.promotion.translate';
        return view('backend.dashboard.layout', compact('template', 'config', 'promotion', 'translate'));
    }

    public function saveTranslate(Request $request)
    {
        if ($this->promotionService->saveTranslate($request)) {
            flash()->success(__('toast.update_success'));
            return redirect()->route('promotion.index');
        }
        flash()->error(__('toast.update_failed'));
        return redirect()->route('promotion.index');
    }

    private function menuItemArgument($whereIn = [])
    {
        $language = $this->language;
        return [
            'condition' => [],
            'flag' => true,
            'relation' => ['languages' => function ($query) use ($language) {
                $query->where('language_id', $language);
            }],
            'orderBy' => ['id', 'DESC'],
            'param' => [
                'whereIn' => $whereIn,
                'whereInField' => 'id'
            ]
        ];
    }

    private function configData()
    {
        return [
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                'backend/plugins/datetimepicker-master/build/jquery.datetimepicker.min.css',
            ],
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/promotion.js',
                'backend/plugins/ckeditor/ckeditor.js',
                'backend/plugins/datetimepicker-master/build/jquery.datetimepicker.full.js',
            ]
        ];
    }
}
