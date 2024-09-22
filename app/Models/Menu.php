<?php

namespace App\Models;

use App\Traits\QueryScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $fillable = [
        'menu_catalogue_id',
        'type',
        'parent_id',
        'lft',
        'rgt',
        'level',
        'image',
        'icon',
        'album',
        'publish',
        'order',
        'user_id',
        'deleted_at',
    ];

    protected $table = 'menus';

    protected $attributes = [
        'order' => 0
    ];

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'menu_language', 'menu_id', 'language_id')->withPivot('menu_id', 'language_id', 'name', 'canonical')->withTimestamps();
    }

    public function menu_catalogues()
    {
        return $this->belongsTo(MenuCatalogue::class, 'menu_catalogue_id', 'id');
    }
}
