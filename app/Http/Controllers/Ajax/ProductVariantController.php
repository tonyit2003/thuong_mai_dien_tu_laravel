<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Services\ProductVariantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ProductVariantController extends Controller
{
    protected $productVariantService;

    public function __construct(ProductVariantService $productVariantService)
    {
        $this->middleware(function ($request, $next) {
            $locale = App::getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
        $this->productVariantService = $productVariantService;
    }

    public function filter(Request $request)
    {
        $productVariants = $this->productVariantService->filter($request);
        /** @var \Illuminate\Support\Collection $productVariants */
        $productVariantUuids = $productVariants->pluck('uuid')->toArray();
        if (count($productVariantUuids) && isset($productVariantUuids)) {
            $productVariants = $this->productVariantService->combineProductVariantAndPromotion($productVariantUuids, $productVariants);
        }
        $productVariants = $this->productVariantService->setInformationFilter($productVariants, $this->language);
        $productVariants = $this->productVariantService->getReview($productVariants);
        $html = $this->renderFilterProduct($productVariants);
        return response()->json([
            'data' => $html,
        ]);
    }

    private function renderFilterProduct($productVariants)
    {
        $html = '';
        if (isset($productVariants) && count($productVariants)) {
            $html .= '<div class="uk-grid uk-grid-medium">';
            foreach ($productVariants as $productVariant) {
                $name = $productVariant->name ?? '';
                $canonical = $productVariant->canonical;
                $image = $productVariant->image;
                $price = getPrice($productVariant);
                $catName = $productVariant->catName ?? '';
                $totalReview = isset($productVariant->reviews) ? $productVariant->reviews->count() : 0;
                $totalRate = isset($productVariant->reviews) ? number_format($productVariant->reviews->avg('score'), 1) : 0;
                $starPercent = isset($productVariant->reviews) ? ($totalRate / 5) * 100 : 0;

                $html .= '<div class="uk-width-1-2 uk-width-small-1-2 uk-width-medium-1-3 uk-width-large-1-5 mb20">';
                $html .= '<div class="product-item product">';

                if ($price['percent'] !== 0) {
                    $html .= "<div class='badge badge-bg2'>-{$price['percent']}%</div>";
                }

                $html .= "<a href='$canonical' class='image img-scaledown img-zoomin'>";
                $html .= "<img src='$image' alt='$name'>";
                $html .= '</a>';

                $html .= '<div class="info">';

                $html .= "<div class='category-title'><a href='$canonical title='$name'>$catName</a></div>";

                $html .= '<h3 class="title">';
                $html .= "<a href='$canonical' title='$name'>$name</a>";
                $html .= '</h3>';

                if ($totalReview !== 0) {
                    $html .= '<div class="rating">';
                    $html .= '<div class="uk-flex uk-flex-middle">';
                    $html .= "<div class='star-rating' style='--star-width: {$starPercent}%'>";
                    $html .= '<div class="stars"></div>';
                    $html .= '</div>';
                    $html .= "<span class='rate-number'>($totalReview)</span>";
                    $html .= '</div>';
                    $html .= '</div>';
                }

                $html .= '<div class="product-group">';
                $html .= '<div class="uk-flex uk-flex-middle uk-flex-space-between">';
                $html .= $price['html'];
                $html .= '<div class="addcart">';
                $html .= '<!-- renderQuickBuy function here if needed -->';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';

                $html .= '</div>';

                $html .= '<div class="tools">';
                $html .= "<a href='$canonical' title='$name'><img src='" . asset('frontend/resources/img/trend.svg') . "' alt='$name'></a>";
                $html .= "<a href='$canonical' title='$name'><img src='" . asset('frontend/resources/img/wishlist.svg') . "' alt='$name'></a>";
                $html .= "<a href='$canonical' title='$name'><img src='" . asset('frontend/resources/img/compare.svg') . "' alt='$name'></a>";
                $html .= "<a href='#popup' data-uk-modal title='$name'><img src='" . asset('frontend/resources/img/view.svg') . "' alt='$name'></a>";
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
            }
            $html .= '</div>';

            $html .= '<div class="uk-flex uk-flex-center">';
            $html .= $productVariants->links('pagination::bootstrap-4');
            $html .= '</div>';
        } else {
            $html = '<div class="no-result">' . __('info.no_reuslt') . '</div>';
        }
        return $html;
    }
}
