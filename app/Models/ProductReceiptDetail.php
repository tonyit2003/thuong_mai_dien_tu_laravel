<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReceiptDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_receipt_id',
        'product_id',
        'product_variant_id',
        'quantity',
        'actual_quantity',
        'price',
    ];

    protected $table = 'product_receipt_detail';

    public function productReceipt()
    {
        return $this->belongsTo(ProductReceipt::class, 'product_receipt_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
