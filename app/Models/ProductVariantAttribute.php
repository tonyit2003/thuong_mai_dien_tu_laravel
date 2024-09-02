<?php

namespace App\Models;

use App\Traits\QueryScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantAttribute extends Model
{
    use HasFactory, QueryScopes;

    protected $fillable = [
        'product_variant_id',
        'attribute_id',
    ];

    protected $table = 'product_variant_attribute';
}
