<?php

namespace App\Services;

use App\Repositories\CustomerRepository;
use App\Repositories\ReviewRepository;
use App\Services\Interfaces\ReviewServiceInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class AttributeCatalogueService
 * @package App\Services
 */
class ReviewService extends BaseService implements ReviewServiceInterface
{
    protected $reviewRepository;
    protected $customerRepository;

    public function __construct(ReviewRepository $reviewRepository, CustomerRepository $customerRepository)
    {
        $this->reviewRepository = $reviewRepository;
        $this->customerRepository = $customerRepository;
    }

    public function create($request)
    {
        DB::beginTransaction();
        try {
            $customer_id = Auth::guard('customers')->id();
            $variant_uuid = $request->input('variant_uuid');
            $content = $request->input('content') ?? '';
            $score = $request->input('score') ?? 5;

            $existingReview = $this->reviewRepository->findByCondition([
                ['customer_id', '=', $customer_id],
                ['variant_uuid', '=', $variant_uuid],
            ]);

            if ($existingReview) {
                $payload = [
                    'content' => $content,
                    'score' => $score,
                ];
                $this->reviewRepository->update($existingReview->id, $payload);
            } else {
                $payload = [
                    'customer_id' => $customer_id,
                    'variant_uuid' => $variant_uuid,
                    'content' => $content,
                    'score' => $score,
                ];
                $this->reviewRepository->create($payload);
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            // dd($e->getMessage());
            DB::rollBack();
            return false;
        }
    }

    public function setCustomerInformation($reviews)
    {
        if (isset($reviews) && count($reviews)) {
            foreach ($reviews as $review) {
                $customer = $this->customerRepository->findById($review->customer_id);
                $review->fullname = $customer->name;
            }
        }
        return $reviews;
    }
}
