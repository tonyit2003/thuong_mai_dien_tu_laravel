<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Repositories\OrderRepository;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class OrderController extends Controller
{
    protected $orderService;
    protected $orderRepository;

    public function __construct(OrderService $orderService, OrderRepository $orderRepository)
    {
        $this->middleware(function ($request, $next) {
            $locale = App::getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
        parent::__construct();
        $this->orderService = $orderService;
        $this->orderRepository = $orderRepository;
    }

    public function update(Request $request)
    {
        $flag = $this->orderService->update($request, $this->system, $this->language);
        $order = $this->orderRepository->findById($request->input('id'));
        $order = $this->orderService->setAddress($order);
        if ($flag) {
            return response()->json([
                'code' => 10,
                'messages' => __('toast.update_success'),
                'order' => $order,
            ]);
        }
        return response()->json([
            'code' => 11,
            'messages' => __('toast.update_failed'),
            'order' => $order,
        ]);
    }

    public function chart(Request $request)
    {
        $locale = app()->getLocale();
        $currency = determineCurrency($locale);
        $chart = $this->orderService->getOrderChart($request);

        if (!isset($chart['data']) || !is_array($chart['data'])) {
            return response()->json(['error' => 'Invalid data format'], 400);
        }

        $formattedData = array_map(function ($value) use ($currency) {
            if ($currency == 'VND') {
                return floatval($value);  
            }

            return floatval(str_replace(['$', ','], '', formatCurrency($value)));
        }, $chart['data']);

        $chart['data'] = $formattedData;

        return response()->json($chart);
    }
}
