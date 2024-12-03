<?php

namespace App\Services;

use App\Repositories\ProductCatalogueRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ProductVariantRepository;
use App\Repositories\PromotionRepository;
use App\Repositories\ReviewRepository;
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
    protected $reviewRepository;
    protected $productRepository;

    public function __construct(ProductVariantRepository $productVariantRepository, PromotionRepository $promotionRepository, ProductCatalogueRepository $productCatalogueRepository, ReviewRepository $reviewRepository, ProductRepository $productRepository)
    {
        $this->productVariantRepository = $productVariantRepository;
        $this->promotionRepository = $promotionRepository;
        $this->productCatalogueRepository = $productCatalogueRepository;
        $this->reviewRepository = $reviewRepository;
        $this->productRepository = $productRepository;
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

    public function getReview($productVariants)
    {
        if (isset($productVariants) && count($productVariants)) {
            foreach ($productVariants as $productVariant) {
                $reviews = $this->reviewRepository->findByCondition([
                    ['variant_uuid', '=', $productVariant->uuid],
                    config('apps.general.publish')
                ], true);
                if (isset($reviews) && count($reviews)) {
                    $productVariant->reviews = $reviews;
                }
            }
        }
        return $productVariants;
    }

    public function setInformationFilter($productVariants, $language)
    {
        if (isset($productVariants) && count($productVariants)) {
            foreach ($productVariants as $item) {
                $uuid = $item->uuid;
                $product = $this->productRepository->getProductByVariant($uuid, $language);
                $productVariant = $this->productVariantRepository->findByCondition([
                    ['uuid', '=', $uuid]
                ], false, ['languages' => function ($query) use ($language) {
                    $query->where('language_id', $language);
                }]);
                $productCatalogue = $this->productCatalogueRepository->findById($product->product_catalogue_id, ['*'], ['languages' => function ($query) use ($language) {
                    $query->where('language_id', $language);
                }]);
                $item->name = $product->languages->first()->pivot->name . ' - ' . $productVariant->languages->first()->pivot->name;
                $item->canonical = write_url($product->languages->first()->pivot->canonical, true, false) . '/uuid=' . $uuid . config('apps.general.suffix');
                $item->image = isset($productVariant->album) ? image(explode(',', $productVariant->album)[0]) : 'backend/img/no-photo.png';
                $item->catName = $productCatalogue->languages->first()->pivot->name;
            }
        }
        return $productVariants;
    }

    public function filter($request)
    {
        $perpage = $request->input('perpage') ?? 20;
        $path = 'ajax/product/filter';
        $param['priceQuery'] = $this->priceQuery($request);
        $param['attributeQuery'] = $this->attributeQuery($request);
        $param['productCatalogueQuery'] = $this->productCatalogueQuery($request);
        $param['sortQuery'] = $this->sortQuery($request);
        $query = $this->combineFilterQuery($param);
        $productVariants = $this->productVariantRepository->filter($query, $perpage, $path);
        return $productVariants;
    }

    public function searchProduct($request, $language)
    {
        $keyword = $request->input('keyword');
        if (isset($keyword)) {
            $path = 'search' . config('apps.general.suffix');
            return $this->productVariantRepository->searchProduct($keyword, $language, $path);
        } else {
            return [];
        }
    }

    private function combineFilterQuery($param)
    {
        $query = [];
        foreach ($param as $array) {
            foreach ($array as $key => $val) {
                if (!isset($query[$key])) {
                    $query[$key] = [];
                }
                if (is_array($val)) {
                    $query[$key] = array_merge($query[$key], $val);
                } else {
                    $query[$key][] = $val;
                }
            }
        }
        return $query;
    }

    private function productCatalogueQuery($request)
    {
        $productCatalogueId = $request->input('productCatalogueId');
        $query['join'] = null;
        $query['whereRaw'] = null;
        $query['select'] = null;
        if ($productCatalogueId > 0) {
            // $query['select'] = ['products.name'];
            $query['join'] = [
                ['products', 'products.id', '=', 'product_variants.product_id'],
                ['product_catalogue_product', 'products.id', '=', 'product_catalogue_product.product_id'],
            ];
            $query['whereRaw'] = [
                [
                    'product_catalogue_product.product_catalogue_id IN (
                        SELECT id
                        FROM product_catalogues
                        WHERE lft >= (SELECT lft FROM product_catalogues WHERE product_catalogues.id = ?)
                        AND rgt <= (SELECT rgt FROM product_catalogues WHERE product_catalogues.id = ?)
                    )',
                    [$productCatalogueId, $productCatalogueId]
                ]
            ];
        }
        return $query;
    }

    private function attributeQuery($request)
    {
        $attributes = $request->input('attributes');
        $query['join'] = null;
        $query['where'] = null;

        if (isset($attributes) && count($attributes)) {
            foreach ($attributes as $key => $attribute) {
                $joinKey = 'tb' . $key;
                $query['join'][] = [
                    "product_variant_attribute as $joinKey",
                    "$joinKey.product_variant_id",
                    '=',
                    'product_variants.id'
                ];
                $query['where'][] = function ($query) use ($joinKey, $attribute) {
                    foreach ($attribute as $attr) {
                        $query->orWhere("$joinKey.attribute_id", '=', $attr);
                    }
                };
            }
        }
        return $query;
    }

    private function priceQuery($request)
    {
        $price = $request->input('price');
        $priceMin = convert_price($price['price_min']);
        $priceMax = convert_price($price['price_max']);
        $query['having'] = null;
        $query['join'] = null;
        $query['select'] = null;
        $query['where'] = null;

        if ($priceMax > $priceMin) {
            $query['join']  = [
                ['promotion_product_variant', 'promotion_product_variant.variant_uuid', '=', 'product_variants.uuid'],
            ];
            $query['select'] = "
                (product_variants.price - COALESCE(MAX(
                IF(
                    promotions.maxDiscountValue != 0,
                    LEAST (
                        CASE
                            WHEN discountType = 'cash' THEN discountValue
                            WHEN discountType = 'percent' THEN product_variants.price * discountValue / 100
                            ELSE 0
                        END,
                        promotions.maxDiscountValue
                    ),
                    CASE
                        WHEN discountType = 'cash' THEN discountValue
                        WHEN discountType = 'percent' THEN product_variants.price * discountValue / 100
                        ELSE 0
                    END
                )
            ), 0)) as discounted_price
            ";
            $query['having'] = function ($query) use ($priceMin, $priceMax) {
                $query->havingRaw('discounted_price >= ? AND discounted_price <= ?', [$priceMin, $priceMax]);
            };
        }
        return $query;
    }

    private function sortQuery($request)
    {
        $sort = $request->input('sort');
        $query['orderBy'] = null;
        if (isset($sort)) {
            switch ($sort) {
                case 'price:asc': {
                        $query['orderBy'][] = ['discounted_price', 'ASC'];
                        break;
                    }
                case 'price:desc': {
                        $query['orderBy'][] = ['discounted_price', 'DESC'];
                        break;
                    }
            }
        }
        return $query;
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
