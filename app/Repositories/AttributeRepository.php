<?php

namespace App\Repositories;

use App\Models\Attribute;
use App\Repositories\Interfaces\AttributeRepositoryInterface;

/**
 * Class AttributesRepository
 * @package App\Repositories
 */
class AttributeRepository extends BaseRepository implements AttributeRepositoryInterface
{
    protected $model;

    public function __construct(Attribute $attribute)
    {
        $this->model = $attribute;
        parent::__construct($this->model); //truyền model lên lớp cha
    }

    public function getAttributeById($id = 0, $language_id = 0)
    {
        return $this->model->select([
            'attributes.id',
            'attributes.attribute_catalogue_id',
            'attributes.image',
            'attributes.icon',
            'attributes.album',
            'attributes.publish',
            'attributes.follow',
            'attribute_language.name',
            'attribute_language.description',
            'attribute_language.content',
            'attribute_language.meta_title',
            'attribute_language.meta_keyword',
            'attribute_language.meta_description',
            'attribute_language.canonical'
        ])->join('attribute_language', 'attribute_language.attribute_id', '=', 'attributes.id')->with('attribute_catalogues')->where('attribute_language.language_id', '=', $language_id)->find($id);
    }

    public function searchAttributes($keyword = '', $option = [], $languageId)
    {
        return $this->model->whereHas('attribute_catalogues', function ($query) use ($option) {
            $query->where('attribute_catalogue_id', $option['attributeCatalogueId']);
        })->whereHas('attribute_language', function ($query) use ($keyword, $languageId) {
            $query->where('language_id', $languageId)->where('name', 'like', '%' . $keyword . '%');
        })->get();
    }

    public function findAttributeByIdArray($attributeArray = [], $languageId, $publish = false)
    {
        $query = $this->model->select([
            'attributes.id',
            'attributes.attribute_catalogue_id',
            'attribute_language.name'
        ])
            ->join('attribute_language', 'attribute_language.attribute_id', '=', 'attributes.id')
            ->where('attribute_language.language_id', '=', $languageId)
            ->whereIn('attributes.id', $attributeArray);

        if ($publish) {
            $query->where([config('apps.general.publish')]);
        }

        return $query->get();
    }

    public function findAttributeProductVariant($attributeIds, $productCatalogueId)
    {
        return $this->model->select(['attributes.id'])
            ->leftJoin('product_variant_attribute', 'product_variant_attribute.attribute_id', '=', 'attributes.id')
            ->leftJoin('product_variants', 'product_variants.id', '=', 'product_variant_attribute.product_variant_id')
            ->leftJoin('product_catalogue_product', 'product_catalogue_product.product_id', '=', 'product_variants.product_id')
            ->where('product_catalogue_product.product_catalogue_id', '=', $productCatalogueId)
            ->whereIn('attributes.id', $attributeIds)
            ->distinct()
            ->pluck('attributes.id');
    }
}
