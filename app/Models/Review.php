<?php

namespace App\Models;

use App\Traits\QueryScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory, QueryScopes;

    protected $fillable = [
        'customer_id',
        'variant_uuid',
        'publish',
        'content',
        'score',
    ];

    protected $table = 'reviews';

    public function product_variants()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_uuid', 'uuid');
    }
}
