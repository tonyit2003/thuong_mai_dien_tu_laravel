<?php

namespace App\Models;

use App\Traits\QueryScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $fillable = [
        'attribute_catalogue_id',
        'image',
        'icon',
        'album',
        'publish',
        'order',
        'user_id',
        'deleted_at',
        'follow'
    ];

    protected $table = 'attributes';

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'attribute_language', 'attribute_id', 'language_id')->withPivot(
            'name',
            'canonical',
            'meta_title',
            'meta_keyword',
            'meta_description',
            'description',
            'content'
        )->withTimestamps();;
    }

    public function attribute_catalogues()
    {
        return $this->belongsToMany(AttributeCatalogue::class, 'attribute_catalogue_attribute', 'attribute_id', 'attribute_catalogue_id');
    }

    public function attribute_language()
    {
        return $this->hasMany(AttributeLanguage::class, 'attribute_id', 'id');
    }

    public function product_variants()
    {
        return $this->belongsToMany(ProductVariant::class, 'product_variant_attribute', 'attribute_id', 'product_variant_id')->withTimestamps();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_attribute', 'attribute_id', 'product_id')->withTimestamps();
    }
}
