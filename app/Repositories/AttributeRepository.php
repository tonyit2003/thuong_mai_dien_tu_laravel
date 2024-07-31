<?php

namespace App\Repositories;

use App\Models\Attribute;
use App\Repositories\Interfaces\AttributeRepositoryInterface;

/**
 * Class AttributesRepository
 * @package App\Repositories
 */
class AttributeRepository extends BaseRepository implements AttributeRepositoryInterface
{
    protected $model;

    public function __construct(Attribute $attribute)
    {
        $this->model = $attribute;
        parent::__construct($this->model); //truyền model lên lớp cha
    }

    public function getAttributeById($id = 0, $language_id = 0)
    {
        return $this->model->select([
            'attributes.id',
            'attributes.attribute_catalogue_id',
            'attributes.image',
            'attributes.icon',
            'attributes.album',
            'attributes.publish',
            'attributes.follow',
            'attribute_language.name',
            'attribute_language.description',
            'attribute_language.content',
            'attribute_language.meta_title',
            'attribute_language.meta_keyword',
            'attribute_language.meta_description',
            'attribute_language.canonical'
        ])->join('attribute_language', 'attribute_language.attribute_id', '=', 'attributes.id')->with('attribute_catalogues')->where('attribute_language.language_id', '=', $language_id)->find($id);
    }
}
