<?php

namespace App\Models;

use App\Traits\QueryScopes;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Supplier extends Model
{
    use HasFactory, Notifiable, SoftDeletes, QueryScopes;

    protected $fillable = [
        'name',
        'address',
        'email',
        'phone',
        'fax',
        'publish',
        'province_id',
        'district_id',
        'ward_id'
    ];

    protected $table = 'suppliers';

    public function product_catalogues()
    {
        return $this->belongsToMany(ProductCatalogue::class, 'product_catalogue_supplier', 'supplier_id', 'product_catalogue_id');
    }

    public function catalogues()
    {
        return $this->belongsToMany(ProductCatalogue::class, 'product_catalogue_supplier', 'supplier_id', 'product_catalogue_id');
    }
}
