<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ProductVariantRepository;
use App\Services\Interfaces\OrderServiceInterface;
use Exception;
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

    public function __construct(ProductRepository $productRepository, ProductVariantRepository $productVariantRepository, OrderRepository $orderRepository)
    {
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->orderRepository = $orderRepository;
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
