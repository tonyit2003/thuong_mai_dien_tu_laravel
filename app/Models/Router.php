<?php

namespace App\Models;

use App\Traits\QueryScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Router extends Model
{
    use HasFactory, QueryScopes;

    protected $fillable = [
        'canonical',
        'module_id',
        'controllers',
        'language_id',
    ];

    protected $table = 'routers';
}
