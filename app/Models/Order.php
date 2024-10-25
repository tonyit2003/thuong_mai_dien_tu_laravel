<?php

namespace App\Models;

use App\Traits\QueryScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $fillable = [
        'customer_id',
        'code',
        'fullname',
        'phone',
        'email',
        'province_id',
        'district_id',
        'ward_id',
        'address',
        'description',
        'promotion',
        'cart',
        'totalPrice',
        'guest_cookie',
        'method',
        'confirm',
        'payment',
        'delivery',
        'shipping',
        'deleted_at'
    ];

    protected $table = 'orders';
    protected $casts = [
        'cart' => 'json',
        'promotion' => 'json'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product', 'order_id', 'product_id')->withPivot(
            'variant_uuid',
            'quantity',
            'price',
            'priceOriginal',
            'promotion',
            'option',
        )->withTimestamps();;
    }

    public function order_payment()
    {
        return $this->hasMany(OrderPayment::class, 'order_id', 'id');
    }
}
