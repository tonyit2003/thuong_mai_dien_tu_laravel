<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'customer_id',
        'variant_uuid',
        'content',
        'score',
    ];

    protected $table = 'reviews';

    public function product_variants()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_uuid', 'uuid');
    }
}
