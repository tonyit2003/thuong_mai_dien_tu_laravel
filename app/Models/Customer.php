<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\QueryScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, QueryScopes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'province_id',
        'district_id',
        'ward_id',
        'address',
        'birthday',
        'image',
        'description',
        'user_agent',
        'ip',
        'customer_catalogue_id',
        'publish',
        'deleted_at',
        'source_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $table = 'customers';
    protected $primaryKey = 'id';
    // thiết lập các giá trị mặc định cho các cột trong cơ sở dữ liệu khi một model mới được tạo
    protected $attributes = [
        'publish' => 1
    ];
    public $incrementing = true;

    public function customer_catalogues()
    {
        return $this->belongsTo(CustomerCatalogue::class, 'customer_catalogue_id', 'id');
    }

    public function sources()
    {
        return $this->belongsTo(Source::class, 'source_id', 'id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'code');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'code');
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id', 'code');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
