<?php

namespace App\Models;

use App\Traits\QueryScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Widget extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $fillable = [
        'name',
        'keyword',
        'short_code',
        'description',
        'album',
        'model_id',
        'model',
        'publish',
        'user_id',
    ];

    protected $table = 'widgets';

    protected $casts = [
        'model_id' => 'json',
        'album' => 'json',
        'description' => 'json',
    ];
}
