<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Repositories\CustomerCatalogueRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\ProvinceRepository;
use App\Repositories\SourceRepository;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CustomerController extends Controller
{
    protected $customerService;
    protected $provinceRepository;
    protected $customerRepository;
    protected $customerCatalogueRepository;
    protected $sourceRepository;

    public function __construct(CustomerService $customerService, ProvinceRepository $provinceRepository, CustomerRepository $customerRepository, CustomerCatalogueRepository $customerCatalogueRepository, SourceRepository $sourceRepository)
    {
        $this->customerService = $customerService;
        $this->provinceRepository = $provinceRepository;
        $this->customerRepository = $customerRepository;
        $this->customerCatalogueRepository = $customerCatalogueRepository;
        $this->sourceRepository = $sourceRepository;
    }

    public function index(Request $request)
    {
        Gate::authorize('modules', 'customer.index');
        $customers = $this->customerService->paginate($request);
        $customerCatalogues = $this->customerCatalogueRepository->all();
        $sources = $this->sourceRepository->all();
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'Customer'
        ];
        $config['seo'] = __('customer');

        $template = 'backend.customer.customer.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'customers', 'customerCatalogues', 'sources'));
    }

    public function create()
    {
        Gate::authorize('modules', 'customer.create');
        $provinces = $this->provinceRepository->all();
        $customerCatalogues = $this->customerCatalogueRepository->all();
        $sources = $this->sourceRepository->all();
        $config = $this->configData();
        $config['seo'] = __('customer');
        $config['method'] = 'create';
        $template = 'backend.customer.customer.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'provinces', 'customerCatalogues', 'sources'));
    }

    public function store(StoreCustomerRequest $storeCustomerRequest)
    {
        if ($this->customerService->create($storeCustomerRequest)) {
            flash()->success(__('toast.store_success'));
            return redirect()->route('customer.index');
        }
        flash()->error(__('toast.store_failed'));
        return redirect()->route('customer.index');
    }

    public function edit($id)
    {
        Gate::authorize('modules', 'customer.update');
        $customer = $this->customerRepository->findById($id);
        $provinces = $this->provinceRepository->all();
        $customerCatalogues = $this->customerCatalogueRepository->all();
        $sources = $this->sourceRepository->all();
        $config = $this->configData();
        $config['seo'] = __('customer');
        $config['method'] = 'edit';
        $template = 'backend.customer.customer.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'provinces', 'customer', 'customerCatalogues', 'sources'));
    }

    public function update($id, UpdateCustomerRequest $updateCustomerRequest)
    {
        if ($this->customerService->update($id, $updateCustomerRequest)) {
            flash()->success(__('toast.update_success'));
            return redirect()->route('customer.index');
        }
        flash()->error(__('toast.update_failed'));
        return redirect()->route('customer.index');
    }

    public function delete($id)
    {
        Gate::authorize('modules', 'customer.destroy');
        $customer = $this->customerRepository->findById($id);
        $config['seo'] = __('customer');
        $template = 'backend.customer.customer.delete';
        return view('backend.dashboard.layout', compact('template', 'customer', 'config'));
    }

    public function destroy($id)
    {
        if ($this->customerService->delete($id)) {
            flash()->success(__('toast.destroy_success'));
            return redirect()->route('customer.index');
        }
        flash()->error(__('toast.destroy_failed'));
        return redirect()->route('customer.index');
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
