<?php

namespace App\Models;

use App\Traits\QueryScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeLanguage extends Model
{
    use HasFactory, QueryScopes;

    protected $table = 'attribute_language';

    public function attributes()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id', 'id');
    }
}
