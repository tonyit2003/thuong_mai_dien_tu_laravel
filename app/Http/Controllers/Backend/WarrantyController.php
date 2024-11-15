<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWarrantyRequest;
use App\Models\Customer;
use App\Models\Language;
use App\Models\ProductVariant;
use App\Models\ProductVariantLanguage;
use App\Repositories\OrderProductRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProvinceRepository;
use App\Repositories\WarrantyRepository;
use App\Services\OrderService;
use App\Services\WarrantyService;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

class WarrantyController extends Controller
{
    protected $orderService;
    protected $orderRepository;
    protected $orderProductRepository;
    protected $provinceRepository;
    protected $warrantyService;
    protected $warrantyRepository;
    public function __construct(OrderService $orderService, OrderRepository $orderRepository, OrderProductRepository $orderProductRepository, ProvinceRepository $provinceRepository, WarrantyService $warrantyService, WarrantyRepository $warrantyRepository)
    {
        $this->orderService = $orderService;
        $this->orderRepository = $orderRepository;
        $this->orderProductRepository = $orderProductRepository;
        $this->provinceRepository = $provinceRepository;
        $this->warrantyService = $warrantyService;
        $this->warrantyRepository = $warrantyRepository;
        parent::__construct();
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

        $orders = collect(); // Khởi tạo một tập hợp rỗng nếu không có từ khóa tìm kiếm

        // Kiểm tra nếu có từ khóa tìm kiếm trong request, chỉ khi đó mới truy vấn
        if ($request->filled('keyword')) {
            $orders = $this->orderService->warrantyPaginate($request);
        }

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
        $warranty_card = $this->warrantyRepository->findByConditionWarranty([['order_id', '=', $order->id]], false);
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
        $template = 'backend.warranty.warranty.detail';
        return view('backend.dashboard.layout', compact('template', 'config', 'order', 'orderProducts', 'provinces', 'warranty_card'));
    }

    public function warrantyConfirm(StoreWarrantyRequest $request)
    {
        // Lấy danh sách các sản phẩm được chọn
        $productIds = $request->input('product_id', []);
        $variantUuids = $request->input('variant_uuid', []);
        $notes = $request->input('notes', []);
        $dateOfReceipts = $request->input('date_of_receipt', []);
        $productName = $request->input('product_name', []);
        $id = $request->integer('order_id');
        $order = $this->orderRepository->findByCondition([['id', '=', $id]], false, ['products']);
        $system = $this->system;

        // Tạo danh sách dữ liệu gửi mail
        $mailData = [];
        foreach ($productIds as $key => $productId) {
            $mailData[] = [
                'product_id' => $productId,
                'variant_uuid' => $variantUuids[$key] ?? null,
                'note' => $notes[$key] ?? null,
                'date_of_receipt' => $dateOfReceipts[$key] ?? null,
                'product_name' => $productName[$key] ?? null
            ];
        }

        if ($this->warrantyService->createOrUpdate($request)) {
            $this->warrantyService->mail($mailData, $order, $system);
            flash()->success(__('toast.store_success'));
            return redirect()->route('warranty.warrantyRepair');
        }
        flash()->error(__('toast.store_failed'));
        return redirect()->route('warranty.warrantyRepair');
    }

    public function warrantyRepair(Request $request)
    {
        Gate::authorize('modules', 'warranty.warrantyRepair');

        $orders = $this->orderService->warrantyRepairPaginate($request);

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
        $config['seo'] = __('warrantyRepair');

        $template = 'backend.warranty.repair.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'orders'));
    }

    public function repairDetail(Request $request, $id)
    {
        $language = $this->language;
        $order = $this->orderRepository->findByCondition([['id', '=', $id]], false, ['products']);
        $order = $this->orderService->setAddress($order);
        $orderProducts = $this->orderProductRepository->findByCondition([['order_id', '=', $order->id]], true);
        $orderProducts = $this->orderService->setInformation($orderProducts, $language);
        $provinces = $this->provinceRepository->all();
        $warranty_card = $this->warrantyRepository->findByConditionWarranty([['order_id', '=', $order->id]], false);
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
        $config['seo'] = __('orderWarranty');
        $template = 'backend.warranty.repair.detail';
        return view('backend.dashboard.layout', compact('template', 'config', 'order', 'orderProducts', 'provinces', 'warranty_card'));
    }

    public function warrantyConfirmRepair(StoreWarrantyRequest $request)
    {
        if ($this->warrantyService->updateRepair($request)) {
            flash()->success(__('toast.update_success'));
            return redirect()->route('warranty.warrantyRepair');
        }
        flash()->error(__('toast.update_failed'));
        return redirect()->route('warranty.warrantyRepair');
    }
}
