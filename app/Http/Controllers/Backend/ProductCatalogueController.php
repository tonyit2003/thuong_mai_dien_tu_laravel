<?php

namespace App\Http\Controllers\Backend;

use App\Classes\Nestedsetbie;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteProductCatalogueRequest;
use App\Http\Requests\StoreProductCatalogueRequest;
use App\Http\Requests\UpdateProductCatalogueRequest;
use App\Models\Language;
use App\Repositories\ProductCatalogueRepository;
use App\Services\ProductCatalogueService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

class ProductCatalogueController extends Controller
{
    protected $productCatalogueService;
    protected $productCatalogueRepository;
    protected $nestedset;
    protected $language;

    public function __construct(ProductCatalogueService $productCatalogueService, ProductCatalogueRepository $productCatalogueRepository)
    {
        $this->middleware(function ($request, $next) {
            $locale = App::getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });
        $this->productCatalogueService = $productCatalogueService;
        $this->productCatalogueRepository = $productCatalogueRepository;
    }

    public function index(Request $request)
    {
        Gate::authorize('modules', 'product.catalogue.index');

        $productCatalogues = $this->productCatalogueService->paginate($request, $this->language);

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'ProductCatalogue'
        ];
        $config['seo'] = __('productCatalogue');
        $template = 'backend.product.catalogue.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'productCatalogues'));
    }

    public function create()
    {
        Gate::authorize('modules', 'product.catalogue.create');
        $config = $this->configData();
        $config['seo'] = __('productCatalogue');
        $config['method'] = 'create';
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.product.catalogue.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'dropdown'));
    }

    public function store(StoreProductCatalogueRequest $storeProductCatalogueRequest)
    {
        if ($this->productCatalogueService->create($storeProductCatalogueRequest, $this->language)) {
            flash()->success(__('toast.store_success'));
            return redirect()->route('product.catalogue.index');
        }
        flash()->error(__('toast.store_failed'));
        return redirect()->route('product.catalogue.index');
    }

    public function edit($id)
    {
        Gate::authorize('modules', 'product.catalogue.update');
        $productCatalogue = $this->productCatalogueRepository->getProductCatalogueById($id, $this->language);
        $config = $this->configData();
        $config['seo'] = __('productCatalogue');
        $config['method'] = 'edit';
        $dropdown = $this->nestedset->Dropdown();
        $album = json_decode($productCatalogue->album);
        $template = 'backend.product.catalogue.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'productCatalogue', 'dropdown', 'album'));
    }

    public function update($id, UpdateProductCatalogueRequest $updateProductCatalogueRequest)
    {
        if ($this->productCatalogueService->update($id, $updateProductCatalogueRequest, $this->language)) {
            flash()->success(__('toast.update_success'));
            return redirect()->route('product.catalogue.index');
        }
        flash()->error(__('toast.update_failed'));
        return redirect()->route('product.catalogue.index');
    }

    public function delete($id)
    {
        Gate::authorize('modules', 'product.catalogue.destroy');
        $productCatalogue = $this->productCatalogueRepository->getProductCatalogueById($id, $this->language);
        $config['seo'] = __('productCatalogue');
        $template = 'backend.product.catalogue.delete';
        return view('backend.dashboard.layout', compact('template', 'productCatalogue', 'config'));
    }

    public function destroy($id, DeleteProductCatalogueRequest $deleteProductCatalogueRequest)
    {
        if ($this->productCatalogueService->delete($id, $this->language)) {
            flash()->success(__('toast.destroy_success'));
            return redirect()->route('product.catalogue.index');
        }
        flash()->error(__('toast.destroy_failed'));
        return redirect()->route('product.catalogue.index');
    }

    private function initialize()
    {
        $this->nestedset = new Nestedsetbie([
            'table' => 'product_catalogues',
            'foreignkey' => 'product_catalogue_id',
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
