<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrantyCard extends Model
{
    use HasFactory;

    protected $table = 'warranty_cards';

    protected $fillable = [
        'order_id',
        'product_id',
        'variant_uuid',
        'warranty_start_date',
        'warranty_end_date',
        'date_of_receipt',
        'status',
        'notes',
        'quantity',
        'user_id'
    ];

    /**
     * Liên kết tới model Order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Liên kết tới model Product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Liên kết tới model ProductVariant thông qua variant_uuid.
     */
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_uuid', 'uuid');
    }
}
