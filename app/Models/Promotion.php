<?php

namespace App\Models;

use App\Traits\QueryScopes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'method',
        'discountInformation',
        'neverEndDate',
        'startDate',
        'endDate',
        'publish',
        'order',
        'deleted_at',
    ];

    protected $casts = [
        'discountInformation' => 'json'
    ];

    protected $table = 'promotions';

    public function products()
    {
        return $this->belongsToMany(Product::class, 'promotion_product_variant', 'promotion_id', 'product_id')->withPivot(
            'product_variant_id',
            'model',
        )->withTimestamps();;
    }
}
