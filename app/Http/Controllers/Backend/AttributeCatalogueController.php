<?php

namespace App\Http\Controllers\Backend;

use App\Classes\Nestedsetbie;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteAttributeCatalogueRequest;
use App\Http\Requests\StoreAttributeCatalogueRequest;
use App\Http\Requests\UpdateAttributeCatalogueRequest;
use App\Models\Language;
use App\Repositories\AttributeCatalogueRepository;
use App\Services\AttributeCatalogueService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

class AttributeCatalogueController extends Controller
{
    protected $attributeCatalogueService;
    protected $attributeCatalogueRepository;
    protected $nestedset;
    protected $language;

    public function __construct(AttributeCatalogueService $attributeCatalogueService, AttributeCatalogueRepository $attributeCatalogueRepository)
    {
        $this->middleware(function ($request, $next) {
            $locale = App::getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });
        $this->attributeCatalogueService = $attributeCatalogueService;
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;
    }

    public function index(Request $request)
    {
        Gate::authorize('modules', 'attribute.catalogue.index');

        $attributeCatalogues = $this->attributeCatalogueService->paginate($request, $this->language);

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'AttributeCatalogue'
        ];
        $config['seo'] = __('attributeCatalogue');
        $template = 'backend.attribute.catalogue.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'attributeCatalogues'));
    }

    public function create()
    {
        Gate::authorize('modules', 'attribute.catalogue.create');
        $config = $this->configData();
        $config['seo'] = __('attributeCatalogue');
        $config['method'] = 'create';
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.attribute.catalogue.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'dropdown'));
    }

    public function store(StoreAttributeCatalogueRequest $storeAttributeCatalogueRequest)
    {
        if ($this->attributeCatalogueService->create($storeAttributeCatalogueRequest, $this->language)) {
            flash()->success(__('toast.store_success'));
            return redirect()->route('attribute.catalogue.index');
        }
        flash()->error(__('toast.store_failed'));
        return redirect()->route('attribute.catalogue.index');
    }

    public function edit($id)
    {
        Gate::authorize('modules', 'attribute.catalogue.update');
        $attributeCatalogue = $this->attributeCatalogueRepository->getAttributeCatalogueById($id, $this->language);
        $config = $this->configData();
        $config['seo'] = __('attributeCatalogue');
        $config['method'] = 'edit';
        $dropdown = $this->nestedset->Dropdown();
        $album = json_decode($attributeCatalogue->album);
        $template = 'backend.attribute.catalogue.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'attributeCatalogue', 'dropdown', 'album'));
    }

    public function update($id, UpdateAttributeCatalogueRequest $updateAttributeCatalogueRequest)
    {
        if ($this->attributeCatalogueService->update($id, $updateAttributeCatalogueRequest, $this->language)) {
            flash()->success(__('toast.update_success'));
            return redirect()->route('attribute.catalogue.index');
        }
        flash()->error(__('toast.update_failed'));
        return redirect()->route('attribute.catalogue.index');
    }

    public function delete($id)
    {
        Gate::authorize('modules', 'attribute.catalogue.destroy');
        $attributeCatalogue = $this->attributeCatalogueRepository->getAttributeCatalogueById($id, $this->language);
        $config['seo'] = __('attributeCatalogue');
        $template = 'backend.attribute.catalogue.delete';
        return view('backend.dashboard.layout', compact('template', 'attributeCatalogue', 'config'));
    }

    public function destroy($id, DeleteAttributeCatalogueRequest $deleteAttributeCatalogueRequest)
    {
        if ($this->attributeCatalogueService->delete($id, $this->language)) {
            flash()->success(__('toast.destroy_success'));
            return redirect()->route('attribute.catalogue.index');
        }
        flash()->error(__('toast.destroy_failed'));
        return redirect()->route('attribute.catalogue.index');
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
