<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeCatalogueLanguage extends Model
{
    use HasFactory;

    protected $table = 'attribute_catalogue_language';

    public function attribute_catalogues()
    {
        return $this->belongsTo(AttributeCatalogue::class, 'attribute_catalogue_id', 'id');
    }
}
