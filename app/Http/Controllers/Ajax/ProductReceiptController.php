<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Repositories\OrderRepository;
use App\Services\OrderService;
use App\Services\ProductReceiptService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ProductReceiptController extends Controller
{
    protected $productReceiptService;

    public function __construct(ProductReceiptService $productReceiptService)
    {
        $this->middleware(function ($request, $next) {
            $locale = App::getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
        parent::__construct();
        $this->productReceiptService = $productReceiptService;
    }

    public function chart(Request $request)
    {
        $locale = app()->getLocale();
        $currency = determineCurrency($locale);
        $chart = $this->productReceiptService->getReceiptChart($request);

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
