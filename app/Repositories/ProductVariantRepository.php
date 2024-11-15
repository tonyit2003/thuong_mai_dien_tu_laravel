<?php

namespace App\Repositories;

use App\Enums\PriceEnum;
use App\Models\ProductVariant;
use App\Repositories\Interfaces\ProductVariantRepositoryInterface;
use Illuminate\Support\Facades\DB;

/**
 * Class ProductsRepository
 * @package App\Repositories
 */
class ProductVariantRepository extends BaseRepository implements ProductVariantRepositoryInterface
{
    protected $model;
    protected $productRepository;

    public function __construct(ProductVariant $productVariant, ProductRepository $productRepository)
    {
        $this->model = $productVariant;
        $this->productRepository = $productRepository;
        parent::__construct($this->model); //truyền model lên lớp cha
    }

    public function updateProductVariantDetails($receiptDetails)
    {
        foreach ($receiptDetails as $detail) {
            $this->model
                ->where('id', $detail->product_variant_id)
                ->update([
                    'quantity' => DB::raw('quantity + ' . $detail->actual_quantity),
                    'price' => $detail->price + ($detail->price * (PriceEnum::PERCENT_PRICE / 100)),
                ]);
            $this->dataSynchronization($detail);
        }
    }

    private function dataSynchronization($detail)
    {
        $productVariant = $this->findById($detail->product_variant_id);
        $sku = $productVariant->sku;

        $product = $this->productRepository->findById($detail->product_id);
        $variants = json_decode($product->variant);
        foreach ($variants->sku as $key => $val) {
            if ($val === $sku) {
                $variants->price[$key] = convert_price(floatval($detail->price) + (floatval($detail->price) * (PriceEnum::PERCENT_PRICE / 100)));
                $variants->quantity[$key] += $detail->actual_quantity;
                break;
            }
        }
        $payload['variant'] = json_encode($variants);
        $this->productRepository->update($detail->product_id, $payload);
    }

    public function findVariant($code, $productId, $languageId)
    {
        return $this->model->select(['id', 'uuid'])->where([
            'code' => $code,
            'product_id' => $productId
        ])
            ->with('languages', function ($query) use ($languageId) {
                $query->where('language_id', $languageId);
            })
            ->first();
    }

    public function filter($param, $perPage, $path)
    {
        $query = $this->model->newQuery();
        $query->select(['product_variants.uuid', 'product_variants.price']);

        if (isset($param['select']) && count($param['select'])) {
            foreach ($param['select'] as $key => $val) {
                if (is_null($val)) continue;
                $query->selectRaw($val);
            }
        }

        if (isset($param['join']) && count($param['join'])) {
            foreach ($param['join'] as $key => $val) {
                if (is_null($val)) continue;
                $query->leftJoin($val[0], $val[1], $val[2], $val[3]);
            }
        }

        $query->where('products.publish', '=', 1);
        $query->where('promotions.publish', '=', 1);
        $query->whereDate('promotions.endDate', '>=', now());
        $query->whereDate('promotions.startDate', '<=', now());
        $query->whereNull('promotions.deleted_at');

        if (isset($param['where']) && count($param['where'])) {
            foreach ($param['where'] as $key => $val) {
                if (is_null($val)) continue;
                $query->where($val);
            }
        }

        if (isset($param['whereRaw']) && count($param['whereRaw'])) {
            foreach ($param['whereRaw'] as $key => $val) {
                if (is_null($val)) continue;
                $query->whereRaw($val[0], $val[1]);
            }
        }

        $query->groupBy(['product_variants.uuid', 'product_variants.price']);

        if (isset($param['having']) && count($param['having'])) {
            foreach ($param['having'] as $key => $val) {
                if (is_null($val)) continue;
                $query->having($val);
            }
        }

        if (isset($param['orderBy']) && count($param['orderBy'])) {
            foreach ($param['orderBy'] as $key => $val) {
                if (is_null($val)) continue;
                $query->orderBy($val[0], $val[1]);
            }
        }

        return $query->paginate($perPage)->withQueryString()->withPath(env('APP_URL') . $path);
    }
}
