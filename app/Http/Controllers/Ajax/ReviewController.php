<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\FrontendController;
use App\Services\ReviewService;
use Illuminate\Http\Request;

class ReviewController extends FrontendController
{
    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        parent::__construct();
        $this->reviewService = $reviewService;
    }

    public function create(Request $request)
    {
        $flag = $this->reviewService->create($request);
        return response()->json([
            'messages' => $flag ? __('toast.review_success') : __('toast.review_fail'),
            'code' => $flag ? 10 : 11
        ]);
    }
}
