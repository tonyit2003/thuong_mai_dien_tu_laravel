<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScopes;

class ProductReceipt extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $fillable = [
        'date_created',
        'publish',
        'user_id',
        'supplier_id',
        'total'
    ];

    protected $table = 'product_receipts';

    public function product_variants()
    {
        return $this->belongsToMany(ProductVariant::class, 'product_receipt_detail', 'product_receipt_id', 'product_variant_id')->withPivot(
            'quantity',
            'price'
        )->withTimestamps();;
    }

    public function details()
    {
        return $this->hasMany(ProductReceiptDetail::class, 'product_receipt_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function suppliers()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
}
