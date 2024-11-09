<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Repositories\ReviewRepository;
use App\Services\ReviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

class ReviewController extends Controller
{
    protected $reviewService;
    protected $reviewRepository;

    public function __construct(ReviewService $reviewService, ReviewRepository $reviewRepository)
    {
        $this->middleware(function ($request, $next) {
            $locale = App::getLocale();
            $language = Language::where('canonical', $locale)->first();
            $this->language = $language->id;
            return $next($request);
        });
        $this->reviewService = $reviewService;
        $this->reviewRepository = $reviewRepository;
    }

    public function index(Request $request)
    {
        Gate::authorize('modules', 'review.index');
        $reviews = $this->reviewService->paginate($request);
        $reviews = $this->reviewService->setCustomerInformation($reviews);
        $reviews = $this->reviewService->setProductVariantInformation($reviews, $this->language);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'Review'
        ];
        $config['seo'] = __('review');

        $template = 'backend.review.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'reviews'));
    }

    public function delete($id)
    {
        Gate::authorize('modules', 'review.destroy');
        $review = $this->reviewRepository->findById($id);
        $config['seo'] = __('review');
        $template = 'backend.review.delete';
        return view('backend.dashboard.layout', compact('template', 'review', 'config'));
    }

    public function destroy($id)
    {
        if ($this->reviewService->delete($id)) {
            flash()->success(__('toast.destroy_success'));
            return redirect()->route('review.index');
        }
        flash()->error(__('toast.destroy_failed'));
        return redirect()->route('review.index');
    }
}
