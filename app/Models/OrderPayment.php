<?php

namespace App\Models;

use App\Traits\QueryScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPayment extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $fillable = [
        'order_id',
        'method_name',
        'payment_id',
        'payment_detail',
    ];

    protected $table = 'order_payment';
    protected $casts = [
        'payment_detail' => 'json',
    ];

    public function orders()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
