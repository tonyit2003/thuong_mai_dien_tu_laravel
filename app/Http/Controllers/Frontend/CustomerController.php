<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Repositories\CustomerRepository;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;

class CustomerController extends FrontendController
{
    protected $customerService;
    protected $customerRepository;
    public function __construct(CustomerService $customerService, CustomerRepository $customerRepository)
    {
        // gọi hàm khởi tạo (constructor) của lớp cha
        parent::__construct();
        $this->customerService = $customerService;
        $this->customerRepository = $customerRepository;
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

    private function config()
    {
        return [
            'css' => [
                'frontend/css/customer.css'
            ],
            'js' => [
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
            ],
        ];
    }
}
