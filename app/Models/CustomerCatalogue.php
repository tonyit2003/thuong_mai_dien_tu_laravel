<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\QueryScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class CustomerCatalogue extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, QueryScopes;

    protected $fillable = [
        'name',
        'description',
        'publish',
        'deleted_at'
    ];

    protected $table = 'customer_catalogues';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $attributes = [
        'publish' => 1
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class, 'customer_catalogue_id', 'id');
    }
}
