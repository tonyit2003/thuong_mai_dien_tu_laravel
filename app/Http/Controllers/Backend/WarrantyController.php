<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Language;
use App\Repositories\OrderProductRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProvinceRepository;
use App\Repositories\UserCatalogueRepository;
use App\Repositories\UserRepository;
use App\Services\OrderService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

class WarrantyController extends Controller
{
    protected $orderService;
    protected $orderRepository;
    protected $orderProductRepository;
    protected $provinceRepository;
    public function __construct(OrderService $orderService, OrderRepository $orderRepository, OrderProductRepository $orderProductRepository, ProvinceRepository $provinceRepository)
    {
        $this->orderService = $orderService;
        $this->orderRepository = $orderRepository;
        $this->orderProductRepository = $orderProductRepository;
        $this->provinceRepository = $provinceRepository;
        $this->middleware(function ($request, $next) {
            $locale = App::getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        Gate::authorize('modules', 'warranty.index');

        $orders = $this->orderService->paginate($request);
        $config = [
            'js' => [
                'backend\js\plugins\toastr\toastr.min.js',
                'backend/js/plugins/switchery/switchery.js',
                'backend/library/order.js',
                'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js',
                'backend/js/plugins/daterangepicker/daterangepicker.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend\css\plugins\toastr\toastr.min.css',
                'backend/css/plugins/switchery/switchery.css',
                'backend/css/plugins/daterangepicker/daterangepicker-bs3.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'Order'
        ];
        $config['seo'] = __('warranty');

        $template = 'backend.warranty.warranty.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'orders'));
    }

    public function detail(Request $request, $id)
    {
        $language = $this->language;
        $order = $this->orderRepository->findByCondition([['id', '=', $id]], false, ['products']);
        $order = $this->orderService->setAddress($order);
        $orderProducts = $this->orderProductRepository->findByCondition([['order_id', '=', $order->id]], true);
        $orderProducts = $this->orderService->setInformation($orderProducts, $language);
        $provinces = $this->provinceRepository->all();
        $config = [
            'css' => [
                'backend\css\plugins\toastr\toastr.min.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'js' => [
                'backend\js\plugins\toastr\toastr.min.js',
                'backend/library/order.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
        ];
        $config['seo'] = __('order');
        $template = 'backend.order.detail';
        return view('backend.dashboard.layout', compact('template', 'config', 'order', 'orderProducts', 'provinces'));
    }
}
