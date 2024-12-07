<?php

namespace App\Http\Controllers\Backend;

use App\Classes\Nestedsetbie;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttributeRequest;
use App\Http\Requests\UpdateAttributeRequest;
use App\Models\Language;
use App\Repositories\AttributeRepository;
use App\Services\AttributeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

class AttributeController extends Controller
{
    protected $attributeService;
    protected $attributeRepository;
    protected $nestedset;
    protected $language;

    public function __construct(AttributeService $attributeService, AttributeRepository $attributeRepository)
    {
        $this->middleware(function ($request, $next) {
            $locale = App::getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });
        $this->attributeService = $attributeService;
        $this->attributeRepository = $attributeRepository;
        $this->initialize();
    }

    public function index(Request $request)
    {
        Gate::authorize('modules', 'attribute.index');
        $attributes = $this->attributeService->paginate($request, $this->language);
        $languageId = $this->language;
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'Attribute'
        ];
        $config['seo'] = __('attribute');
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.attribute.attribute.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'attributes', 'dropdown', 'languageId'));
    }

    public function create()
    {
        Gate::authorize('modules', 'attribute.create');
        $config = $this->configData();
        $config['seo'] = __('attribute');
        $config['method'] = 'create';
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.attribute.attribute.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'dropdown'));
    }

    public function store(StoreAttributeRequest $storeAttributeRequest)
    {
        if ($this->attributeService->create($storeAttributeRequest, $this->language)) {
            flash()->success(__('toast.store_success'));
            return redirect()->route('attribute.index');
        }
        flash()->error(__('toast.store_failed'));
        return redirect()->route('attribute.index');
    }

    public function edit($id)
    {
        Gate::authorize('modules', 'attribute.update');
        $attribute = $this->attributeRepository->getAttributeById($id, $this->language);
        $config = $this->configData();
        $config['seo'] = __('attribute');
        $config['method'] = 'edit';
        $album = json_decode($attribute->album);
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.attribute.attribute.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'attribute', 'album', 'dropdown'));
    }

    public function update($id, UpdateAttributeRequest $updateAttributeRequest)
    {
        if ($this->attributeService->update($id, $updateAttributeRequest, $this->language)) {
            flash()->success(__('toast.update_success'));
            return redirect()->route('attribute.index');
        }
        flash()->error(__('toast.update_failed'));
        return redirect()->route('attribute.index');
    }

    public function delete($id)
    {
        Gate::authorize('modules', 'attribute.destroy');
        $attribute = $this->attributeRepository->getAttributeById($id, $this->language);
        $config['seo'] = __('attribute');
        $template = 'backend.attribute.attribute.delete';
        return view('backend.dashboard.layout', compact('template', 'attribute', 'config'));
    }

    public function destroy($id)
    {
        if ($this->attributeService->delete($id)) {
            flash()->success(__('toast.destroy_success'));
            return redirect()->route('attribute.index');
        }
        flash()->error(__('toast.destroy_failed'));
        return redirect()->route('attribute.index');
    }

    private function initialize()
    {
        $this->nestedset = new Nestedsetbie([
            'table' => 'attribute_catalogues',
            'foreignkey' => 'attribute_catalogue_id',
            'language_id' => $this->language
        ]);
    }

    private function configData()
    {
        return [
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
    }
}
