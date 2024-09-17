<?php

namespace App\Services;

use App\Enums\PromotionEnum;
use App\Repositories\PromotionRepository;
use App\Services\Interfaces\PromotionServiceInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class PromotionService
 * @package App\Services
 */
class PromotionService extends BaseService implements PromotionServiceInterface
{
    protected $promotionRepository;

    public function __construct(PromotionRepository $promotionRepository)
    {
        $this->promotionRepository = $promotionRepository;
    }

    public function paginate($request)
    {
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->input('publish') != null ? $request->integer('publish') : -1;
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        return $this->promotionRepository->pagination($this->paginateSelect(), $condition, [], $perPage, ['path' => 'promotion/index']);
    }

    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $this->request($request);
            $promotion = $this->promotionRepository->create($payload);
            if ($promotion->id > 0 && $request->input('method') === PromotionEnum::PRODUCT_AND_QUANTITY) {
                $this->createPromotionProductVariant($request, $promotion);
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            dd($e->getLine());
            DB::rollBack();
            return false;
        }
    }

    public function update($id, $request)
    {
        DB::beginTransaction();
        try {
            $payload = $this->request($request);
            $promotion = $this->promotionRepository->update($id, $payload);
            if ($promotion->id > 0 && $request->input('method') === PromotionEnum::PRODUCT_AND_QUANTITY) {
                $this->createPromotionProductVariant($request, $promotion);
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return false;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $this->promotionRepository->delete($id);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    private function request($request)
    {
        $payload = $request->only('name', 'code', 'description', 'method', 'startDate', 'endDate', 'neverEndDate');
        $payload['startDate'] = Carbon::createFromFormat('d/m/Y H:i', $payload['startDate']);
        if (isset($payload['endDate'])) {
            $payload['endDate'] = Carbon::createFromFormat('d/m/Y H:i', $payload['endDate']);
        }
        switch ($payload['method']) {
            case PromotionEnum::ORDER_AMOUNT_RANGE:
                $payload[PromotionEnum::DISCOUNT_INFORMATION] = $this->orderByRange($request);
                break;

            case PromotionEnum::PRODUCT_AND_QUANTITY:
                $payload[PromotionEnum::DISCOUNT_INFORMATION] = $this->productAndQuantity($request);
                break;
        }
        return $payload;
    }

    private function orderByRange($request)
    {
        $data['info'] = $request->input('promotion_order_amount_range');
        return $data + $this->handleSourceAndApply($request);
    }

    private function productAndQuantity($request)
    {
        $data['info'] = $request->input('product_and_quantity');
        $data['info']['model'] = $request->input(PromotionEnum::MODULE_TYPE);
        $data['info'][PromotionEnum::OBJECT] = $request->input(PromotionEnum::OBJECT);
        return $data + $this->handleSourceAndApply($request);
    }

    private function handleSourceAndApply($request)
    {
        $data = [
            'source' => [
                'status' => $request->input('sourceStatus'),
                'data' => $request->input('sourceValue'),
            ],
            'apply' => [
                'status' => $request->input('applyStatus'),
                'data' => $request->input('applyValue'),
            ],
        ];
        if (isset($data['apply']['data'])) {
            foreach ($data['apply']['data'] as $key => $val) {
                $data['apply']['condition'][$val] = $request->input($val);
            }
        }
        return $data;
    }

    private function createPromotionProductVariant($request, $promotion)
    {
        $object = $request->input(PromotionEnum::OBJECT);
        $payload = [];
        if (isset($object)) {
            foreach ($object['id'] as $key => $val) {
                $payload[] = [
                    'product_id' => $val,
                    'product_variant_id' => $object['product_variant_id'][$key] !== 'null' ? $object['product_variant_id'][$key] : 0,
                    'model' => $request->input(PromotionEnum::MODULE_TYPE)
                ];
            }
        }
        // đồng bộ các bảng ghi trong bảng trung gian theo $promotion->id
        $promotion->products()->sync($payload);
    }

    private function paginateSelect()
    {
        return ['id', 'name', 'code', 'discountInformation', 'method', 'neverEndDate', 'startDate', 'endDate', 'publish', 'order'];
    }
}
