<?php

namespace App\Services;

use App\Repositories\CustomerRepository;
use App\Repositories\OrderProductRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
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
    protected $orderRepository;
    protected $orderProductRepository;
    protected $productRepository;

    public function __construct(ReviewRepository $reviewRepository, CustomerRepository $customerRepository, OrderRepository $orderRepository, OrderProductRepository $orderProductRepository, ProductRepository $productRepository)
    {
        $this->reviewRepository = $reviewRepository;
        $this->customerRepository = $customerRepository;
        $this->orderRepository = $orderRepository;
        $this->orderProductRepository = $orderProductRepository;
        $this->productRepository = $productRepository;
    }

    public function paginate($request)
    {
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->input('publish') != null ? $request->integer('publish') : -1;
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        return $this->reviewRepository->pagination($this->paginateSelect(), $condition, [], $perPage, ['path' => 'review/index']);
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

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $this->reviewRepository->delete($id);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function checkPurchasedProduct($request)
    {
        $customer_id = Auth::guard('customers')->id();
        $variant_uuid = $request->input('variant_uuid');
        return $this->orderProductRepository->checkProductVariantExists($variant_uuid, $customer_id);
    }

    public function setCustomerInformation($reviews)
    {
        if (isset($reviews) && count($reviews)) {
            foreach ($reviews as $review) {
                $customer = $this->customerRepository->findById($review->customer_id);
                $review->fullname = $customer->name;
                $review->email = $customer->email;
            }
        }
        return $reviews;
    }

    public function setProductVariantInformation($reviews, $language)
    {
        if (isset($reviews) && count($reviews)) {
            foreach ($reviews as $review) {
                $product = $this->productRepository->getProductByVariant($review->variant_uuid, $language);
                if (isset($product)) {
                    $review->product_canonical = $product->languages->first()->pivot->canonical;
                }
            }
        }
        return $reviews;
    }

    private function paginateSelect()
    {
        return ['id', 'customer_id', 'variant_uuid', 'content', 'score', 'publish', 'updated_at'];
    }
}
