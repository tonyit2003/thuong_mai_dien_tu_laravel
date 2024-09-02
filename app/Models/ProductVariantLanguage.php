<?php

namespace App\Models;

use App\Traits\QueryScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantLanguage extends Model
{
    use HasFactory, QueryScopes;

    protected $fillable = [
        'product_variant_id',
        'language_id',
        'name',
    ];

    protected $table = 'product_variant_language';

    // thêm dữ liệu bằng insert() => thêm dữ liệu vào created_at và updated_at
    public $timestamps = true;
}
