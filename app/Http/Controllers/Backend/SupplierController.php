<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\ProductCatalogueRepository;
use App\Repositories\ProvinceRepository;
use App\Repositories\SupplierRepository;
use App\Repositories\UserCatalogueRepository;
use App\Repositories\UserRepository;
use App\Services\SupplierService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SupplierController extends Controller
{
    protected $supplierService;
    protected $supplierRepository;
    protected $provinceRepository;
    protected $productCatalogueRepository;

    public function __construct(SupplierService $supplierService, SupplierRepository $supplierRepository, ProvinceRepository $provinceRepository, ProductCatalogueRepository $productCatalogueRepository)
    {
        $this->supplierService = $supplierService;
        $this->supplierRepository = $supplierRepository;
        $this->provinceRepository = $provinceRepository;
        $this->productCatalogueRepository = $productCatalogueRepository;
    }

    public function index(Request $request)
    {
        Gate::authorize('modules', 'supplier.index');
        $suppliers = $this->supplierService->paginate($request);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'Supplier'
        ];
        $config['seo'] = __('supplier');

        $template = 'backend.supplier.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'suppliers'));
    }

    public function create()
    {
        Gate::authorize('modules', 'supplier.create');
        $provinces = $this->provinceRepository->all();
        $productCatalogues = $this->productCatalogueRepository->all(
            ['product_catalogue_language']
        );

        $config = $this->configData();
        $config['seo'] = __('supplier');
        $config['method'] = 'create';
        $template = 'backend.supplier.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'provinces', 'productCatalogues'));
    }

    public function store(StoreSupplierRequest $storesSupplierRequest)
    {
        if ($this->supplierService->create($storesSupplierRequest)) {
            flash()->success(__('toast.store_success'));
            return redirect()->route('supplier.index');
        }
        flash()->error(__('toast.store_failed'));
        return redirect()->route('supplier.index');
    }

    public function edit($id)
    {
        Gate::authorize('modules', 'supplier.update');
        $supplier = $this->supplierRepository->findById($id);
        $provinces = $this->provinceRepository->all();
        $productCatalogues = $this->productCatalogueRepository->all(
            ['product_catalogue_language']
        );
        $selectedCatalogues = $supplier->catalogues->pluck('id')->toArray();
        $config = $this->configData();
        $config['seo'] = __('supplier');
        $config['method'] = 'edit';
        $template = 'backend.supplier.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'supplier', 'provinces', 'productCatalogues', 'selectedCatalogues'));
    }

    public function update($id, UpdateSupplierRequest $updateSupplierRequest)
    {
        if ($this->supplierService->update($id, $updateSupplierRequest)) {
            flash()->success(__('toast.update_success'));
            return redirect()->route('supplier.index');
        }
        flash()->error(__('toast.update_failed'));
        return redirect()->route('supplier.index');
    }

    public function delete($id)
    {
        Gate::authorize('modules', 'user.destroy');
        $supplier = $this->supplierRepository->findById($id);
        $config['seo'] = __('supplier');
        $template = 'backend.supplier.delete';
        return view('backend.dashboard.layout', compact('template', 'supplier', 'config'));
    }

    public function destroy($id)
    {
        if ($this->supplierService->delete($id)) {
            flash()->success(__('toast.destroy_success'));
            return redirect()->route('supplier.index');
        }
        flash()->error(__('toast.destroy_failed'));
        return redirect()->route('supplier.index');
    }

    private function configData()
    {
        return [
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/library/location.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js'
            ]
        ];
    }
}
