<?php

namespace App\Http\Controllers\Backend;

use App\Exports\ProductReceiptExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApprovedReceiptRequest;
use App\Http\Requests\ExpectedDeliveryDateRequest;
use App\Http\Requests\StoreProductReceiptRequest;
use App\Http\Requests\UpdateProductReceiptRequest;
use App\Models\Language;
use App\Repositories\ProductRepository;
use App\Repositories\ProductReceiptRepository;
use App\Repositories\SupplierRepository;
use App\Repositories\UserRepository;
use App\Services\ProductReceiptService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class ProductReceiptController extends Controller
{
    protected $productReceiptService;
    protected $productReceiptRepository;
    protected $language;
    protected $productRepository;
    protected $productService;
    protected $supplierRepository;
    protected $userRepository;

    public function __construct(ProductReceiptService $productReceiptService, ProductReceiptRepository $productReceiptRepository, ProductRepository $productRepository, ProductService $productService, SupplierRepository $supplierRepository, UserRepository $userRepository)
    {
        $this->middleware(function ($request, $next) {
            $locale = App::getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->productReceiptService = $productReceiptService;
        $this->productReceiptRepository = $productReceiptRepository;
        $this->productRepository = $productRepository;
        $this->supplierRepository = $supplierRepository;
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        Gate::authorize('modules', 'receipt.index');
        $productReceipts = $this->productReceiptService->paginate($request, $this->language);

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/plugins/datetimepicker-master/build/jquery.datetimepicker.full.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                'backend/plugins/datetimepicker-master/build/jquery.datetimepicker.min.css',
            ],
            'model' => 'ProductReceipt'
        ];
        $config['seo'] = __('receipt');
        $template = 'backend.receipt.index';

        $suppliers = $this->supplierRepository->all();
        $users = $this->userRepository->all();
        return view('backend.dashboard.layout', compact('template', 'config', 'productReceipts', 'suppliers', 'users'));
    }

    public function monitor(Request $request)
    {
        Gate::authorize('modules', 'monitor.receipt');
        $productReceipts = $this->productReceiptService->paginate($request, $this->language);

        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/plugins/datetimepicker-master/build/jquery.datetimepicker.full.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'backend/plugins/datetimepicker-master/build/jquery.datetimepicker.min.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'ProductReceipt'
        ];
        $config['seo'] = __('monitor');
        $template = 'backend.receipt.monitor';

        $suppliers = $this->supplierRepository->all();
        $users = $this->userRepository->all();
        return view('backend.dashboard.layout', compact('template', 'config', 'productReceipts', 'suppliers', 'users'));
    }

    public function create(Request $request)
    {
        Gate::authorize('modules', 'receipt.create');

        $config = $this->configData();
        $config['seo'] = __('receipt');
        $config['method'] = 'create';
        $template = 'backend.receipt.store';
        $products = $this->productService->paginate($request, $this->language);
        $suppliers = $this->supplierRepository->all();
        return view('backend.dashboard.layout', compact('template', 'config', 'products', 'suppliers'));
    }

    public function store(StoreProductReceiptRequest $storeProductReceiptRequest)
    {
        if ($this->productReceiptService->create($storeProductReceiptRequest, $this->language)) {
            flash()->success(__('toast.store_success'));
            return response()->json([
                'success' => true,
                'message' => __('toast.store_success'),
                'redirect_url' => route('receipt.index')
            ]);
        }
        flash()->error(__('toast.store_failed'));
        return response()->json([
            'success' => false,
            'message' => __('toast.store_failed')
        ]);
    }

    public function edit($id, Request $request)
    {
        Gate::authorize('modules', 'receipt.update');
        $productReceipt = $this->productReceiptRepository->getProductReceiptById($id);
        $config = $this->configData();
        $config['seo'] = __('receipt');
        $config['method'] = 'edit';
        $template = 'backend.receipt.store';
        $products = $this->productService->paginate($request, $this->language);
        $suppliers = $this->supplierRepository->all();
        return view('backend.dashboard.layout', compact('template', 'config', 'productReceipt', 'products', 'suppliers'));
    }

    public function update($id, UpdateProductReceiptRequest $updateProductReceiptRequest)
    {
        if ($this->productReceiptService->update($id, $updateProductReceiptRequest, $this->language)) {
            flash()->success(__('toast.update_success'));
            return response()->json([
                'success' => true,
                'message' => __('toast.update_success'),
                'redirect_url' => route('receipt.index')
            ]);
        }
        flash()->error(__('toast.update_failed'));
        return response()->json([
            'success' => false,
            'message' => __('toast.update_failed')
        ]);
    }

    public function delete($id)
    {
        Gate::authorize('modules', 'receipt.destroy');
        $productReceipt = $this->productReceiptRepository->getProductReceiptById($id);
        $config['seo'] = __('product');
        $template = 'backend.receipt.delete';
        return view('backend.dashboard.layout', compact('template', 'productReceipt', 'config'));
    }

    public function destroy($id)
    {
        if ($this->productReceiptService->delete($id)) {
            flash()->success(__('toast.destroy_success'));
            return redirect()->route('receipt.index');
        }
        flash()->error(__('toast.destroy_failed'));
        return redirect()->route('receipt.index');
    }

    public function detail($id)
    {
        Gate::authorize('modules', 'receipt.detail');
        $productReceipt = $this->productReceiptRepository->getProductReceiptById($id);
        $config = $this->configData();
        $config['seo'] = __('receipt');
        $config['method'] = 'edit';
        $template = 'backend.receipt.detail';
        $suppliers = $this->supplierRepository->all();

        $formattedDetails = $productReceipt->details->map(function ($detail) {
            return [
                'product_id' => (int)$detail->product_id,
                'variant_id' => (int)$detail->product_variant_id,
                'product_name' => $detail->product->product_name ?? 'N/A',
                'variant_name' => $detail->productVariant->variant_name ?? 'N/A',
                'quantity' => $detail->quantity,
                'actual_quantity' => $detail->actual_quantity,
                'price' => (float)$detail->price
            ];
        });

        return view('backend.dashboard.layout', compact('template', 'config', 'productReceipt', 'suppliers', 'formattedDetails'));
    }

    public function browse($id, Request $request)
    {
        Gate::authorize('modules', 'receipt.browse');
        $productReceipt = $this->productReceiptRepository->getProductReceiptById($id);
        $config = $this->configData();
        $config['seo'] = __('receipt');
        $config['method'] = 'edit';
        $template = 'backend.receipt.browse';
        $products = $this->productService->paginate($request, $this->language);
        $suppliers = $this->supplierRepository->all();

        $formattedDetails = $productReceipt->details->map(function ($detail) {
            return [
                'product_id' => (int)$detail->product_id,
                'variant_id' => (int)$detail->product_variant_id,
                'product_name' => $detail->product->product_name ?? 'N/A',
                'variant_name' => $detail->productVariant->variant_name ?? 'N/A',
                'quantity' => $detail->quantity,
                'actual_quantity' => $detail->actual_quantity,
                'price' => (float)$detail->price
            ];
        });

        return view('backend.dashboard.layout', compact('template', 'config', 'productReceipt', 'products', 'suppliers', 'formattedDetails'));
    }

    public function approve($id, ExpectedDeliveryDateRequest $request)
    {
        if ($this->productReceiptService->approve($id, $request)) {
            flash()->success(__('toast.approve_success'));
            return response()->json([
                'success' => true,
                'message' => __('toast.approve_success'),
                'redirect_url' => route('receipt.success', ['code' => $id])
            ]);
        }
        flash()->error(__('toast.approve_failed'));
        return response()->json([
            'success' => false,
            'message' => __('toast.approve_failed')
        ]);
    }

    public function success($id, Request $request)
    {
        Gate::authorize('modules', 'receipt.browse');
        $productReceipt = $this->productReceiptRepository->getProductReceiptById($id);
        $formattedDetails = $productReceipt->details->map(function ($detail) {
            return [
                'product_id' => (int)$detail->product_id,
                'variant_id' => (int)$detail->product_variant_id,
                'product_name' => $detail->product->product_name ?? 'N/A',
                'variant_name' => $detail->productVariant->variant_name ?? 'N/A',
                'quantity' => $detail->quantity,
                'actual_quantity' => $detail->actual_quantity,
                'price' => (float)$detail->price
            ];
        });
        $system = $this->system;
        $this->productReceiptService->mail($productReceipt->suppliers->email, $productReceipt, $formattedDetails, $system);
        return redirect()->route('receipt.monitor');
    }

    public function instock($id)
    {
        Gate::authorize('modules', 'receipt.instock');
        $productReceipt = $this->productReceiptRepository->getProductReceiptById($id);
        $config = $this->configData();
        $config['seo'] = __('receipt');
        $config['method'] = 'edit';
        $template = 'backend.receipt.instock';
        $suppliers = $this->supplierRepository->all();

        $formattedDetails = $productReceipt->details->map(function ($detail) {
            return [
                'product_id' => (int)$detail->product_id,
                'variant_id' => (int)$detail->product_variant_id,
                'product_name' => $detail->product->product_name ?? 'N/A',
                'variant_name' => $detail->productVariant->variant_name ?? 'N/A',
                'quantity' => $detail->quantity,
                'actual_quantity' => $detail->actual_quantity,
                'price' => (float)$detail->price
            ];
        });

        return view('backend.dashboard.layout', compact('template', 'config', 'productReceipt', 'suppliers', 'formattedDetails'));
    }

    public function delivere($id, ApprovedReceiptRequest $request)
    {
        if ($this->productReceiptService->delivere($id, $request)) {
            flash()->success(__('toast.delivere_success'));
            return redirect()->route('receipt.index');
        }
        flash()->error(__('toast.delivere_failed'));
        return redirect()->route('receipt.index');
    }
    public function statisticalReceipt()
    {
        $receiptStatistic = $this->productReceiptService->statistic();
        //$customerStatistic = $this->customerService->statistic();
        $config = $this->config();
        $template = 'backend.dashboard.home.statistical';
        return view('backend.dashboard.layout', compact('template', 'config', 'receiptStatistic'));
    }

    private function config()
    {
        return [
            'js' => [
                'backend/js/plugins/chartJs/Chart.min.js',
                'backend/library/dashboard.js',
                // 'backend/js/plugins/flot/jquery.flot.js',
                // 'backend/js/plugins/flot/jquery.flot.tooltip.min.js',
                // 'backend/js/plugins/flot/jquery.flot.spline.js',
                // 'backend/js/plugins/flot/jquery.flot.resize.js',
                // 'backend/js/plugins/flot/jquery.flot.pie.js',
                // 'backend/js/plugins/flot/jquery.flot.symbol.js',
                // 'backend/js/plugins/flot/jquery.flot.time.js',
                // 'backend/js/plugins/peity/jquery.peity.min.js',
                // 'backend/js/demo/peity-demo.js',
                // 'backend/js/inspinia.js',
                // 'backend/js/plugins/pace/pace.min.js',
                // 'backend/js/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js',
                // 'backend/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js',
                // 'backend/js/plugins/easypiechart/jquery.easypiechart.js',
                // 'backend/js/plugins/sparkline/jquery.sparkline.min.js',
                // 'backend/js/demo/sparkline-demo.js'
            ]
        ];
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
                'backend/library/receipt.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/plugins/nice-select/js/jquery.nice-select.min.js',
                'backend/plugins/datetimepicker-master/build/jquery.datetimepicker.full.js',
                'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                'backend/plugins/nice-select/css/nice-select.css',
                'backend/plugins/datetimepicker-master/build/jquery.datetimepicker.min.css',
                'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css'
            ]
        ];
    }
}
