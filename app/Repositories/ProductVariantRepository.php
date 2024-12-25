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
                    'quantity_entered' => DB::raw('quantity_entered + ' . $detail->actual_quantity)
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

        $query->leftJoin('promotions', function ($join) {
            $join->on('promotion_product_variant.promotion_id', '=', 'promotions.id')
                ->where('promotions.publish', 1)
                ->whereDate('promotions.endDate', '>=', now())
                ->whereDate('promotions.startDate', '<=', now())
                ->whereNull('promotions.deleted_at');
        });


        $query->where('products.publish', '=', 1);

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

    public function searchProduct($searchTerm = '', $language = 1, $path = '', $perPage = 20)
    {
        $keywords = explode(' ', $searchTerm);
        $query = $this->model
            ->join('product_variant_language', 'product_variants.id', '=', 'product_variant_language.product_variant_id')
            ->join('products', 'products.id', '=', 'product_variants.product_id')
            ->join('product_language', 'products.id', '=', 'product_language.product_id')
            ->where('product_language.language_id', '=', $language)
            ->where('product_variant_language.language_id', '=', value: $language)
            ->select('product_language.name as product_name', 'product_variants.price', 'products.product_catalogue_id', 'product_variants.uuid', 'product_variants.quantity', 'product_variant_language.name as name', 'product_variants.album', 'product_language.canonical as product_canonical');

        foreach ($keywords as $keyword) {
            $query->where(function ($subQuery) use ($keyword) {
                $subQuery->where('product_language.name', 'LIKE', "%{$keyword}%")
                    ->orWhere('product_variant_language.name', 'LIKE', "%{$keyword}%");
            });
        }

        return $query->paginate($perPage)->withQueryString()->withPath(env('APP_URL') . $path);
    }
}
