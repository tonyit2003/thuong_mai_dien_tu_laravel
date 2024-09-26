<?php

namespace App\Models;

use App\Traits\QueryScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $fillable = [
        'product_catalogue_id',
        'image',
        'icon',
        'album',
        'publish',
        'order',
        'user_id',
        'deleted_at',
        'follow',
        'price',
        'code',
        'made_in',
        'attributeCatalogue',
        'attribute',
        'variant',
    ];

    protected $table = 'products';

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'product_language', 'product_id', 'language_id')->withPivot(
            'name',
            'canonical',
            'meta_title',
            'meta_keyword',
            'meta_description',
            'description',
            'content'
        )->withTimestamps();;
    }

    public function product_catalogues()
    {
        return $this->belongsToMany(ProductCatalogue::class, 'product_catalogue_product', 'product_id', 'product_catalogue_id')->withPivot('product_catalogue_id', 'product_id');
    }

    public function product_variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'id');
    }

    public function product_variant_language()
    {
        return $this->hasManyThrough(
            ProductVariantLanguage::class, // Model đích là ProductVariantLanguage
            ProductVariant::class,         // Model trung gian là ProductVariant
            'product_id',                  // Khóa ngoại trong bảng ProductVariant trỏ đến bảng hiện tại (Product)
            'product_variant_id',          // Khóa ngoại trong bảng ProductVariantLanguage trỏ đến bảng ProductVariant
            'id',                          // Khóa chính của bảng hiện tại (Product)
            'id'                           // Khóa chính của bảng trung gian (ProductVariant)
        );
    }

    public function promotions()
    {
        return $this->belongsToMany(Promotion::class, 'promotion_product_variant', 'product_id', 'promotion_id')->withPivot(
            'variant_uuid',
            'model',
        )->withTimestamps();
    }
}
