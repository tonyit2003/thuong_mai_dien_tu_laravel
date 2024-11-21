<?php

namespace App\Models;

use App\Traits\QueryScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory, QueryScopes, SoftDeletes;

    protected $fillable = [
        'product_id',
        'code',
        'quantity',
        'sku',
        'price',
        'barcode',
        'file_name',
        'file_url',
        'album',
        'publish',
        'user_id',
        'uuid',
    ];

    protected $table = 'product_variants';

    protected $casts = [
        'album' => 'json',
    ];

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function languages()
    {
        return $this->belongsToMany(Language::class, 'product_variant_language', 'product_variant_id', 'language_id')->withPivot(
            'name',
        )->withTimestamps();;
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_variant_attribute', 'product_variant_id', 'attribute_id')->withTimestamps();
    }

    public function product_variant_language()
    {
        return $this->hasMany(ProductVariantLanguage::class, 'product_variant_id', 'id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'variant_uuid', 'uuid');
    }
}
