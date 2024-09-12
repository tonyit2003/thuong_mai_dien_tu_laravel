<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWidgetRequest;
use App\Http\Requests\UpdateWidgetRequest;
use App\Models\Language;
use App\Repositories\LanguageRepository;
use App\Repositories\WidgetRepository;
use App\Services\WidgetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

class WidgetController extends Controller
{
    protected $widgetService;
    protected $widgetRepository;
    protected $languageRepository;

    public function __construct(WidgetService $widgetService, WidgetRepository $widgetRepository, LanguageRepository $languageRepository)
    {
        $this->widgetService = $widgetService;
        $this->widgetRepository = $widgetRepository;
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
        Gate::authorize('modules', 'widget.index');
        $widgets = $this->widgetService->paginate($request);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'Widget'
        ];
        $config['seo'] = __('widget');

        $template = 'backend.widget.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'widgets'));
    }

    public function create()
    {
        Gate::authorize('modules', 'widget.create');
        $config = $this->configData();
        $config['seo'] = __('widget');
        $config['method'] = 'create';
        $template = 'backend.widget.store';
        return view('backend.dashboard.layout', compact('template', 'config'));
    }

    public function store(StoreWidgetRequest $storeWidgetRequest)
    {
        if ($this->widgetService->create($storeWidgetRequest, $this->language)) {
            flash()->success(__('toast.store_success'));
            return redirect()->route('widget.index');
        }
        flash()->error(__('toast.store_failed'));
        return redirect()->route('widget.index');
    }

    public function edit($id)
    {
        Gate::authorize('modules', 'widget.update');
        $widget = $this->widgetRepository->findById($id);
        $widget->description = $widget->description[$this->language];
        /**
         * @var Widget $widget
         */
        $modelClass = loadClass($widget->model);
        $fields = ['id', 'name.languages', 'image'];
        $widgetItem = $modelClass->findByCondition(...array_values($this->menuItemArgument($widget->model_id)));
        $modelItem = convertArrayByKey($widgetItem, $fields);
        $album = $widget->album;
        $config = $this->configData();
        $config['seo'] = __('widget');
        $config['method'] = 'edit';
        $template = 'backend.widget.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'widget', 'album', 'modelItem'));
    }

    public function update($id, UpdateWidgetRequest $updateWidgetRequest)
    {
        if ($this->widgetService->update($id, $updateWidgetRequest, $this->language)) {
            flash()->success(__('toast.update_success'));
            return redirect()->route('widget.index');
        }
        flash()->error(__('toast.update_failed'));
        return redirect()->route('widget.index');
    }

    public function delete($id)
    {
        Gate::authorize('modules', 'widget.destroy');
        $widget = $this->widgetRepository->findById($id);
        $config['seo'] = __('widget');
        $template = 'backend.widget.delete';
        return view('backend.dashboard.layout', compact('template', 'widget', 'config'));
    }

    public function destroy($id)
    {
        if ($this->widgetService->delete($id)) {
            flash()->success(__('toast.destroy_success'));
            return redirect()->route('widget.index');
        }
        flash()->error(__('toast.destroy_failed'));
        return redirect()->route('widget.index');
    }

    public function translate($languageId, $widgetId)
    {
        $translate = $this->languageRepository->findById($languageId);
        $widget = $this->widgetRepository->findById($widgetId);
        $widgetDescription = $widget->description;
        $widget->description = $widgetDescription[$this->language];
        $widget->translateDescription = $widgetDescription[$languageId] ?? null;
        $config = $this->configData();
        $config['seo'] = __('widget', ['language' => lcfirst($translate->name)]);
        $config['method'] = 'translate';
        $template = 'backend.widget.translate';
        return view('backend.dashboard.layout', compact('template', 'config', 'widget', 'translate'));
    }

    public function saveTranslate(Request $request)
    {
        if ($this->widgetService->saveTranslate($request)) {
            flash()->success(__('toast.update_success'));
            return redirect()->route('widget.index');
        }
        flash()->error(__('toast.update_failed'));
        return redirect()->route('widget.index');
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
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/widget.js',
                'backend/plugins/ckeditor/ckeditor.js',
            ]
        ];
    }
}
