<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Repositories\CustomerRepository;
use App\Repositories\ProvinceRepository;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;

class CustomerController extends FrontendController
{
    protected $customerService;
    protected $customerRepository;
    protected $provinceRepository;
    public function __construct(CustomerService $customerService, CustomerRepository $customerRepository, ProvinceRepository $provinceRepository)
    {
        // gọi hàm khởi tạo (constructor) của lớp cha
        parent::__construct();
        $this->customerService = $customerService;
        $this->customerRepository = $customerRepository;
        $this->provinceRepository = $provinceRepository;
    }

    public function info()
    {
        $id = Auth::guard('customers')->user()->id;
        $language = $this->language;
        $system = $this->system;
        $seo = [
            'meta_title' => $system['seo_meta_title'],
            'meta_keyword' => $system['seo_meta_keyword'],
            'meta_description' => $system['seo_meta_description'],
            'meta_image' => $system['seo_meta_image'],
            'canonical' => config('app.url')
        ];
        $customer = $this->customerRepository->findById($id);
        $config = $this->config();
        return view('frontend.customer.info', compact('config', 'language', 'system', 'seo', 'customer'));
    }

    public function updateInfo(Request $storeCustomerRequest)
    {
        $id = Auth::guard('customers')->user()->id;
        if ($this->customerService->updateInfo($id, $storeCustomerRequest)) {
            flash()->success(__('toast.store_success'));
            return redirect()->route('customer.info');
        }
        flash()->error(__('toast.store_failed'));
        return redirect()->route('customer.info');
    }

    public function address()
    {
        $id = Auth::guard('customers')->user()->id;
        $language = $this->language;
        $system = $this->system;
        $provinces = $this->provinceRepository->all();
        $seo = [
            'meta_title' => $system['seo_meta_title'],
            'meta_keyword' => $system['seo_meta_keyword'],
            'meta_description' => $system['seo_meta_description'],
            'meta_image' => $system['seo_meta_image'],
            'canonical' => config('app.url')
        ];
        $customer = $this->customerRepository->findById($id);
        $config = $this->config();
        return view('frontend.customer.address', compact('config', 'language', 'system', 'seo', 'customer', 'provinces'));
    }

    public function updateAddress(Request $storeCustomerRequest)
    {
        $id = Auth::guard('customers')->user()->id;
        if ($this->customerService->updateAddress($id, $storeCustomerRequest)) {
            flash()->success(__('toast.store_success'));
            return redirect()->route('customer.address');
        }
        flash()->error(__('toast.store_failed'));
        return redirect()->route('customer.address');
    }

    private function config()
    {
        return [
            'css' => [
                'frontend/css/customer.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                'backend/css/bootstrap.min.css'
            ],
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/location.js',
                'backend/js/bootstrap.min.js',
                'frontend/core/library/cart.js'
            ],
        ];
    }
}
