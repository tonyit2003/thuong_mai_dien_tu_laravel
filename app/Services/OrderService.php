<?php

namespace App\Services;

use App\Enums\OrderEnum;
use App\Models\Order;
use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ProductVariantRepository;
use App\Services\Interfaces\OrderServiceInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class AttributeCatalogueService
 * @package App\Services
 */
class OrderService implements OrderServiceInterface
{
    protected $productRepository;
    protected $orderRepository;
    protected $productVariantRepository;
    protected $cartRepository;
    protected $cartService;

    public function __construct(ProductRepository $productRepository, ProductVariantRepository $productVariantRepository, OrderRepository $orderRepository, CartRepository $cartRepository, CartService $cartService)
    {
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->orderRepository = $orderRepository;
        $this->cartRepository = $cartRepository;
        $this->cartService = $cartService;
    }

    public function paginate($request)
    {
        $condition['keyword'] = addslashes($request->input('keyword'));
        foreach (__('statusOrder') as $key => $val) {
            $condition['dropdown'][$key] = $request->string($key);
        }
        $condition['created_at'] = $request->input('created_at');
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        return $this->orderRepository->pagination($this->paginateSelect(), $condition, [], $perPage, ['path' => 'order/index']);
    }

    public function warrantyPaginate($request)
    {
        $condition['keyword'] = addslashes($request->input('keyword'));
        foreach (__('statusOrder') as $key => $val) {
            $condition['dropdown'][$key] = $request->string($key);
        }
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        return $this->orderRepository->paginationWarranty($this->paginateSelect(), $condition, [], $perPage, ['path' => 'warranty/index']);
    }

    public function warrantyRepairPaginate($request)
    {
        $condition['keyword'] = addslashes($request->input('keyword'));
        foreach (__('statusOrder') as $key => $val) {
            $condition['dropdown'][$key] = $request->string($key);
        }
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        return $this->orderRepository->paginationRepairWarranty($this->paginateSelect(), $condition, [], $perPage, ['path' => 'warranty/warrantyRepair']);
    }

    public function create($orderCode, $language)
    {
        DB::beginTransaction();
        try {
            $carts = $this->cartRepository->findByCondition([
                ['customer_id', '=', Auth::guard('customers')->id()]
            ], true);
            $carts = $this->cartService->setInformation($carts, $language);
            $cartPromotion = $this->cartService->cartPromotion($carts);
            $totalPriceOriginal = $this->cartService->getTotalPrice($carts);
            $totalPrice = $this->cartService->getTotalPricePromotion($totalPriceOriginal, $cartPromotion['discount']);

            $payload = $this->request($cartPromotion, $totalPrice, $totalPriceOriginal, $orderCode);

            $order = $this->orderRepository->create($payload);
            if ($order->id > 0) {
                $this->createOrderProduct($order, $carts);
                $this->cartRepository->deleteByCondition([
                    ['customer_id', '=', Auth::guard('customers')->id()],
                ]);
                session()->forget('customer_data');
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return false;
        }
    }

    public function update($request)
    {
        DB::beginTransaction();
        try {
            $id = $request->input('id');
            $payload = $request->input('payload');
            if (isset($payload['ward_id']) || isset($payload['district_id']) || isset($payload['province_id'])) {
                if (in_array(null, $payload, true)) {
                    DB::rollBack();
                    return false;
                }
            }
            $this->orderRepository->update($id, $payload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function updateVNPay($payload, $order)
    {
        DB::beginTransaction();
        try {
            $this->orderRepository->update($order->id, $payload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function setAddress($order)
    {
        if (isset($order)) {
            $order->ward = getAddress(null, null, $order->ward_id, null);
            $order->district = getAddress(null, $order->district_id, null, null);
            $order->province = getAddress($order->province_id, null, null, null);
        }
        return $order;
    }

    public function setInformation($orderProducts = null, $language = 1)
    {
        if (isset($orderProducts) && count($orderProducts)) {
            foreach ($orderProducts as $key => $val) {
                $product = $this->productRepository->findById($val->product_id, ['*'], [
                    'languages' => function ($query) use ($language) {
                        $query->where('language_id', $language);
                    }
                ]);
                $productVariant = $this->productVariantRepository->findByCondition([
                    ['uuid', '=', $val->variant_uuid],
                ], false, [
                    'languages' => function ($query) use ($language) {
                        $query->where('language_id', $language);
                    }
                ]);
                $val->name = $product->languages->first()->pivot->name . ' - ' .  $productVariant->languages->first()->pivot->name;
                $val->warranty_time = $product->warranty_time;
                $val->image = isset($productVariant->album) ? explode(',', $productVariant->album)[0] : null;
            }
        }
        return $orderProducts;
    }

    public function getOrderCode()
    {
        // $latestOrder = Order::latest()->first();
        // $orderId = $latestOrder ? $latestOrder->id : 0;
        // return Auth::guard('customers')->id() . '-' . (OrderEnum::ORDER_CODE + $orderId) + 1;
        return time();
    }

    private function request($cartPromotion, $totalPrice, $totalPriceOriginal, $orderCode)
    {
        $payload = session('customer_data');
        $payload['customer_id'] = Auth::guard('customers')->id();
        if (isset($cartPromotion['promotion'])) {
            $payload['promotion']['discount'] = $cartPromotion['discount'];
            $payload['promotion']['name'] = $cartPromotion['promotion']->name;
            $payload['promotion']['code'] = $cartPromotion['promotion']->code;
            $payload['promotion']['startDate'] = $cartPromotion['promotion']->startDate;
            $payload['promotion']['endDate'] = $cartPromotion['promotion']->endDate;
        }
        $payload['code'] = $orderCode;
        $payload['totalPrice'] = $totalPrice;
        $payload['totalPriceOriginal'] = $totalPriceOriginal;
        $payload['confirm'] = 'pending';
        $payload['delivery'] = 'pending';
        if ($payload['method'] != 'cod') {
            $payload['payment'] = 'paid';
        } else {
            $payload['payment'] = 'unpaid';
        }

        return $payload;
    }

    private function createOrderProduct($order, $carts)
    {
        foreach ($carts as $key => $val) {
            $existingRecord = $order->products()
                ->wherePivot('product_id', $val->product_id)
                ->wherePivot('variant_uuid', $val->variant_uuid)
                ->first();

            if ($existingRecord) {
                // Nếu bản ghi đã tồn tại với product_id, order_id và variant_uuid, thì cập nhật dữ liệu khác
                $order->products()->updateExistingPivot($val->product_id, [
                    'variant_uuid' => $val->variant_uuid,
                    'quantity' => $val->quantity,
                    'price' => $val->priceUnit,
                    'priceOriginal' => $this->productVariantRepository
                        ->findByCondition([['uuid', '=', $val->variant_uuid]])
                        ->price,
                ]);
            } else {
                // Nếu chưa tồn tại, thêm bản ghi mới
                $order->products()->attach($val->product_id, [
                    'variant_uuid' => $val->variant_uuid,
                    'quantity' => $val->quantity,
                    'price' => $val->priceUnit,
                    'priceOriginal' => $this->productVariantRepository
                        ->findByCondition([['uuid', '=', $val->variant_uuid]])
                        ->price,
                ]);
            }
        }
    }

    private function paginateSelect()
    {
        return [
            'id',
            'customer_id',
            'code',
            'fullname',
            'phone',
            'email',
            'province_id',
            'district_id',
            'ward_id',
            'address',
            'description',
            'promotion',
            'cart',
            'totalPrice',
            'totalPriceOriginal',
            'guest_cookie',
            'method',
            'confirm',
            'payment',
            'delivery',
            'shipping',
            'deleted_at',
            'created_at',
        ];
    }
}
