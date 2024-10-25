<?php

namespace App\Models;

use App\Traits\QueryScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProduct extends Model
{
    use HasFactory, QueryScopes;

    protected $fillable = [
        'order_id',
        'product_id',
        'variant_uuid',
        'quantity',
        'price',
        'priceOriginal',
        'promotion',
        'option',
    ];

    protected $table = 'order_product';
}
