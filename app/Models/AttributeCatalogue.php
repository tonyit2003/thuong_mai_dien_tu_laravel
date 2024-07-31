<?php

namespace App\Models;

use App\Traits\QueryScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class AttributeCatalogue extends Model
{
    use HasFactory, SoftDeletes, QueryScopes; // SoftDeletes: phương thức delete() sẽ xóa mềm (không xóa dữ liệu trong mysql)

    protected $fillable = [
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
        'follow'
    ];

    protected $table = 'attribute_catalogues';

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'attribute_catalogue_language', 'attribute_catalogue_id', 'language_id')->withPivot('attribute_catalogue_id', 'language_id', 'name', 'canonical', 'meta_title', 'meta_keyword', 'meta_description', 'description', 'content')->withTimestamps();
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'attribute_catalogue_attribute', 'attribute_catalogue_id', 'attribute_id');
    }

    public function attribute_catalogue_language()
    {
        return $this->hasMany(AttributeCatalogueLanguage::class, 'attribute_catalogue_id', 'id');
    }

    public static function isNodeCheck($id = 0)
    {
        $attributeCatalogue = AttributeCatalogue::find($id);

        if ($attributeCatalogue->rgt - $attributeCatalogue->lft !== 1) {
            return false;
        }

        return true;
    }
}
