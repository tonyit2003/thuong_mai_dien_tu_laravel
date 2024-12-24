<?php

namespace App\Http\Controllers\Backend;

use App\Classes\Nestedsetbie;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Language;
use App\Repositories\AttributeCatalogueRepository;
use App\Repositories\ProductCatalogueRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ProductVariantRepository;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    protected $productService;
    protected $productRepository;
    protected $nestedset;
    protected $language;
    protected $attributeCatalogueRepository;
    protected $productVariantRepository;
    protected $productCatalogueRepository;

    public function __construct(ProductService $productService, ProductRepository $productRepository, AttributeCatalogueRepository $attributeCatalogueRepository, ProductVariantRepository $productVariantRepository, ProductCatalogueRepository $productCatalogueRepository)
    {
        $this->middleware(function ($request, $next) {
            $locale = App::getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });
        $this->productService = $productService;
        $this->productRepository = $productRepository;
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->productCatalogueRepository = $productCatalogueRepository;
        $this->initialize();
    }

    public function index(Request $request)
    {
        Gate::authorize('modules', 'product.index');
        $products = $this->productService->paginate($request, $this->language);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'Product'
        ];
        $languageId = $this->language;
        $config['seo'] = __('product');
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.product.product.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'products', 'dropdown', 'languageId'));
    }

    public function create()
    {
        Gate::authorize('modules', 'product.create');
        $language = $this->language;
        $attributeCatalogues = $this->attributeCatalogueRepository->getAll($language, true);
        $config = $this->configData();
        $config['seo'] = __('product');
        $config['method'] = 'create';
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.product.product.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'dropdown', 'attributeCatalogues', 'language'));
    }

    public function store(StoreProductRequest $storeProductRequest)
    {
        if ($this->productService->create($storeProductRequest, $this->language)) {
            flash()->success(__('toast.store_success'));
            return redirect()->route('product.index');
        }
        flash()->error(__('toast.store_failed'));
        return redirect()->route('product.index');
    }

    public function edit($id)
    {
        Gate::authorize('modules', 'product.update');
        $product = $this->productRepository->getProductById($id, $this->language);
        $language = $this->language;
        $attributeCatalogues = $this->attributeCatalogueRepository->getAll($language, true);
        $config = $this->configData();
        $config['seo'] = __('product');
        $config['method'] = 'edit';
        $album = json_decode($product->album);
        $dropdown = $this->nestedset->Dropdown();
        $template = 'backend.product.product.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'product', 'album', 'dropdown', 'attributeCatalogues', 'language'));
    }

    public function update($id, UpdateProductRequest $updateProductRequest)
    {
        if ($this->productService->update($id, $updateProductRequest, $this->language)) {
            flash()->success(__('toast.update_success'));
            return redirect()->route('product.index');
        }
        flash()->error(__('toast.update_failed'));
        return redirect()->route('product.index');
    }

    public function delete($id)
    {
        Gate::authorize('modules', 'product.destroy');
        $product = $this->productRepository->getProductById($id, $this->language);
        $config['seo'] = __('product');
        $template = 'backend.product.product.delete';
        return view('backend.dashboard.layout', compact('template', 'product', 'config'));
    }

    public function destroy($id)
    {
        if ($this->productService->delete($id)) {
            flash()->success(__('toast.destroy_success'));
            return redirect()->route('product.index');
        }
        flash()->error(__('toast.destroy_failed'));
        return redirect()->route('product.index');
    }

    public function viewVariant($id)
    {
        Gate::authorize('modules', 'product.viewVariant');
        $language = $this->language;
        $product = $this->productRepository->getProductById($id, $language);
        $productCatalogue = $this->productCatalogueRepository->findById($product->product_catalogue_id, ['*'], [
            'languages' => function ($query) use ($language) {
                $query->where('language_id', $language);
            }
        ]);
        $productVariants = $this->productVariantRepository->findByCondition([
            ['product_id', '=', $id],
        ], true, [
            'languages' => function ($query) use ($language) {
                $query->where('language_id', $language);
            }
        ]);
        $config = [
            'js' => [
                'backend/js/plugins/metisMenu/jquery.metisMenu.js',
                'backend/js/plugins/slimscroll/jquery.slimscroll.min.js',
                'backend/js/inspinia.js',
                'backend/js/plugins/pace/pace.min.js',
                'backend/js/plugins/footable/footable.all.min.js',
                'backend/js/plugins/blueimp/jquery.blueimp-gallery.min.js',
            ],
            'css' => [
                'backend/css/plugins/footable/footable.core.css',
                'backend/css/plugins/blueimp/css/blueimp-gallery.min.css',
            ]
        ];
        $config['seo'] = __('product');
        $album = json_decode($product->album);
        $template = 'backend.product.product.viewVariant';
        return view('backend.dashboard.layout', compact('template', 'config', 'product', 'album', 'productVariants', 'language', 'productCatalogue'));
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
                'backend/js/plugins/switchery/switchery.js',
                'backend/plugins/ckeditor/ckeditor.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/seo.js',
                'backend/library/variant.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/plugins/nice-select/js/jquery.nice-select.min.js',
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                'backend/plugins/nice-select/css/nice-select.css',
            ]
        ];
    }
}
