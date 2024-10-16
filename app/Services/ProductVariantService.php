<?php

namespace App\Services;

use App\Repositories\ProductCatalogueRepository;
use App\Repositories\ProductVariantRepository;
use App\Repositories\PromotionRepository;
use App\Services\Interfaces\ProductVariantServiceInterface;
use Illuminate\Pagination\Paginator;

/**
 * Class ProductCatalogueService
 * @package App\Services
 */
class ProductVariantService extends BaseService implements ProductVariantServiceInterface
{
    protected $productVariantRepository;
    protected $productCatalogueRepository;
    protected $promotionRepository;

    public function __construct(ProductVariantRepository $productVariantRepository, PromotionRepository $promotionRepository, ProductCatalogueRepository $productCatalogueRepository)
    {
        $this->productVariantRepository = $productVariantRepository;
        $this->promotionRepository = $promotionRepository;
        $this->productCatalogueRepository = $productCatalogueRepository;
    }

    public function paginate($request, $languageId, $productCatalogue = null, $extend = [], $page = 1)
    {
        if (isset($productCatalogue)) {
            // kiểm soát trang hiện tại một cách thủ công, thay vì để Laravel tự động lấy từ URL
            Paginator::currentPageResolver(function () use ($page) {
                return $page;
            });
        }
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->input('publish') != null ? $request->integer('publish') : -1,
            'where' => [
                ['product_variant_language.language_id', '=', $languageId],
                ['product_language.language_id', '=', $languageId],
                ['products.publish', '=', 1],
            ]
        ];
        $join = [
            ['product_variant_language', 'product_variant_language.product_variant_id', '=', 'product_variants.id'],
            ['products', 'products.id', '=', 'product_variants.product_id'],
            ['product_language', 'product_language.product_id', '=', 'products.id'],
            ['product_catalogue_product', 'product_catalogue_product.product_id', '=', 'products.id'],
        ];
        $orderBy = [
            'products.id',
            'DESC'
        ];
        $paginationConfig = [
            'path' => isset($extend['path']) ? $extend['path'] : '',
        ];
        $relations = ['products'];
        $productVariants = $this->productVariantRepository->pagination($this->paginateSelect(), $condition, $join, $perPage, $paginationConfig, $relations, $orderBy, $this->whereRaw($request, $languageId, $productCatalogue));
        return $productVariants;
    }

    public function combineProductVariantAndPromotion($productVariantUuids, $productVariants, $flag = false)
    {
        $promotions = $this->promotionRepository->findByProductVariant($productVariantUuids);
        if ($promotions) {
            if ($flag == true) {
                $productVariants->promotions = $promotions[0] ?? [];
                return $productVariants;
            }
            foreach ($productVariants as $keyProduct => $valProduct) {
                foreach ($promotions as $keyPromotion => $valPromotion) {
                    if ($valPromotion->product_variant_uuid === $valProduct->uuid) {
                        $productVariants[$keyProduct]->promotions = $valPromotion;
                    }
                }
            }
        }
        return $productVariants;
    }

    public function getCatalogueName($productVariants = [], $language = 1)
    {
        if (isset($productVariants) && count($productVariants)) {
            foreach ($productVariants as $key => $val) {
                $productCatalogue = $this->productCatalogueRepository->findById($val->product_catalogue_id, ['*'], ['languages' => function ($query) use ($language) {
                    $query->where('language_id', $language);
                }]);
                $val->product_catalogue = $productCatalogue;
            }
        }
    }

    private function whereRaw($request, $languageId, $productCatalogue = null)
    {
        $rawCondition = [];
        $productCatalogueId = $request->input('product_catalogue_id') != null ? $request->integer('product_catalogue_id') : 0;
        if ($productCatalogueId > 0 || isset($productCatalogue)) {
            $catId = ($productCatalogueId > 0 && $productCatalogue == null) ? $productCatalogueId : $productCatalogue->id;
            $rawCondition['whereRaw'] = [
                [
                    'product_catalogue_product.product_catalogue_id IN (
                        SELECT id
                        FROM product_catalogues
                        JOIN product_catalogue_language ON product_catalogues.id = product_catalogue_language.product_catalogue_id
                        WHERE lft >= (SELECT lft FROM product_catalogues WHERE product_catalogues.id = ?)
                        AND rgt <= (SELECT rgt FROM product_catalogues WHERE product_catalogues.id = ?)
                        AND product_catalogue_language.language_id = ?
                    )',
                    [$catId, $catId, $languageId]
                ]
            ];
        }
        return $rawCondition;
    }

    private function paginateSelect()
    {
        return [
            'product_variants.id',
            'product_variants.uuid',
            'product_variants.code',
            'product_variants.quantity',
            'product_variants.album',
            'product_variants.price',
            'product_variant_language.name',
            'products.id as product_id',
            'products.publish',
            'products.product_catalogue_id',
            'products.image',
            'products.order',
            'products.price as product_price',
            'product_language.name as product_name',
            'product_language.canonical as product_canonical',
        ];
    }
}
