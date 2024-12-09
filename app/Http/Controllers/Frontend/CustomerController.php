<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Http\Requests\UpdateChangePasswordRequest;
use App\Repositories\CustomerRepository;
use App\Repositories\ProvinceRepository;
use App\Services\CustomerService;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Jenssegers\Agent\Agent;
use Str;

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
        $this->setLanguage();
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
        $this->setLanguage();
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

    public function changePassword()
    {
        $this->setLanguage();
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
        return view('frontend.customer.changePassword', compact('config', 'language', 'system', 'seo', 'customer', 'provinces'));
    }

    public function sendChangePassword(Request $request)
    {
        try {
            $this->setLanguage();
            $user = Auth::guard('customers')->user();
            $system = $this->system;

            // Lấy thông tin IP và thiết bị
            $ipAddress = $request->ip();
            $hostname = gethostbyaddr($ipAddress);
            $agent = new \Jenssegers\Agent\Agent();
            $browser = $agent->browser();
            $platform = $agent->platform();
            $device = $agent->isDesktop() ? 'Desktop' : $agent->device();
            $currentTime = now()->toDateTimeString();

            // Tạo token bảo mật
            $token = Str::random(32);
            $expiry = now()->addMinutes(10);

            // Lưu token vào cache với thời gian hết hạn
            Cache::put("change_password_token_{$user->id}", $token, $expiry);

            // Tạo liên kết với token
            $link = route('customer.change') . '?token=' . $token;

            // Gửi email
            $this->customerService->mail($user, $system, $hostname, $ipAddress, $browser, $platform, $device, $currentTime, $link);

            // Ẩn email
            $customerEmail = $user->email;
            $hiddenEmail = preg_replace('/(?<=.{1}).(?=.*@)/', '*', $customerEmail);

            // Truyền dữ liệu vào view nếu cần hiển thị thông tin sau khi gửi email
            $config = $this->config();
            $language = $this->language;
            $seo = [
                'meta_title' => $system['seo_meta_title'],
                'meta_keyword' => $system['seo_meta_keyword'],
                'meta_description' => $system['seo_meta_description'],
                'meta_image' => $system['seo_meta_image'],
                'canonical' => config('app.url')
            ];
            $customer = $this->customerRepository->findById($user->id);
            return view('frontend.customer.emailLink', compact('config', 'language', 'system', 'seo', 'customer', 'hiddenEmail'));
        } catch (\Exception $e) {
            // Trả về thông báo lỗi hoặc view tương ứng
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi gửi email. Vui lòng thử lại sau.');
        }
    }

    public function change(Request $request)
    {
        $this->setLanguage();
        $user = Auth::guard('customers')->user();
        $token = $request->query('token');
        $cachedToken = Cache::get("change_password_token_{$user->id}");

        if (!$token || $token !== $cachedToken) {
            abort(404); // Token không hợp lệ hoặc hết hạn
        }

        // Token hợp lệ, xóa token khỏi cache để tránh sử dụng lại
        Cache::forget("change_password_token_{$user->id}");

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

        return view('frontend.customer.change', compact('config', 'language', 'system', 'seo', 'customer', 'provinces'));
    }

    public function updateChangePassword(UpdateChangePasswordRequest $storeCustomerRequest)
    {
        $id = Auth::guard('customers')->user()->id;
        if ($this->customerService->changePass($id, $storeCustomerRequest)) {
            flash()->success(__('toast.store_success'));
            return redirect()->route('customer.info');
        }
        flash()->error(__('toast.store_failed'));
        return redirect()->route('customer.changePassword');
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
