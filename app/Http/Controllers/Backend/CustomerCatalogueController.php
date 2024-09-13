<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreCustomerCatalogueRequest;
use App\Repositories\CustomerCatalogueRepository;
use App\Services\CustomerCatalogueService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CustomerCatalogueController extends Controller
{
    protected $customerCatalogueService;
    protected $customerCatalogueRepository;

    public function __construct(CustomerCatalogueService $customerCatalogueService, CustomerCatalogueRepository $customerCatalogueRepository)
    {
        $this->customerCatalogueService = $customerCatalogueService;
        $this->customerCatalogueRepository = $customerCatalogueRepository;
    }

    public function index(Request $request)
    {
        Gate::authorize('modules', 'customer.catalogue.index');
        $customerCatalogues = $this->customerCatalogueService->paginate($request);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'CustomerCatalogue'
        ];
        $config['seo'] = __('customerCatalogue');
        $template = 'backend.customer.catalogue.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'customerCatalogues'));
    }

    public function create()
    {
        Gate::authorize('modules', 'customer.catalogue.create');
        $config['seo'] = __('customerCatalogue');
        $config['method'] = 'create';
        $template = 'backend.customer.catalogue.store';
        return view('backend.dashboard.layout', compact('template', 'config'));
    }

    public function store(StoreCustomerCatalogueRequest $storeCustomerCatalogueRequest)
    {
        if ($this->customerCatalogueService->create($storeCustomerCatalogueRequest)) {
            flash()->success(__('toast.store_success'));
            return redirect()->route('customer.catalogue.index');
        }
        flash()->error(__('toast.store_failed'));
        return redirect()->route('customer.catalogue.index');
    }

    public function edit($id)
    {
        Gate::authorize('modules', 'customer.catalogue.update');
        $customerCatalogue = $this->customerCatalogueRepository->findById($id);
        $config['seo'] = __('customerCatalogue');
        $config['method'] = 'edit';
        $template = 'backend.customer.catalogue.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'customerCatalogue'));
    }

    public function update($id, StoreCustomerCatalogueRequest $storeCustomerCatalogueRequest)
    {
        if ($this->customerCatalogueService->update($id, $storeCustomerCatalogueRequest)) {
            flash()->success(__('toast.update_success'));
            return redirect()->route('customer.catalogue.index');
        }
        flash()->error(__('toast.update_failed'));
        return redirect()->route('customer.catalogue.index');
    }

    public function delete($id)
    {
        Gate::authorize('modules', 'customer.catalogue.destroy');
        $customerCatalogue = $this->customerCatalogueRepository->findById($id);
        $config['seo'] = __('customerCatalogue');
        $template = 'backend.customer.catalogue.delete';
        return view('backend.dashboard.layout', compact('template', 'customerCatalogue', 'config'));
    }

    public function destroy($id)
    {
        if ($this->customerCatalogueService->delete($id)) {
            flash()->success(__('toast.destroy_success'));
            return redirect()->route('customer.catalogue.index');
        }
        flash()->error(__('toast.destroy_failed'));
        return redirect()->route('customer.catalogue.index');
    }
}
