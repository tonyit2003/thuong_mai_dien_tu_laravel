<?php

namespace App\Repositories;

use App\Models\AttributeCatalogue;
use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface;

/**
 * Class AttributeCatalogueRepository
 * @package App\Repositories
 */
class AttributeCatalogueRepository extends BaseRepository implements AttributeCatalogueRepositoryInterface
{
    protected $model;

    public function __construct(AttributeCatalogue $attributeCatalogue)
    {
        $this->model = $attributeCatalogue;
        parent::__construct($this->model); //truyền model lên lớp cha
    }

    public function getAttributeCatalogueById($id = 0, $language_id = 0)
    {
        return $this->model->select([
            'attribute_catalogues.id',
            'attribute_catalogues.parent_id',
            'attribute_catalogues.image',
            'attribute_catalogues.icon',
            'attribute_catalogues.album',
            'attribute_catalogues.publish',
            'attribute_catalogues.follow',
            'attribute_catalogue_language.name',
            'attribute_catalogue_language.description',
            'attribute_catalogue_language.content',
            'attribute_catalogue_language.meta_title',
            'attribute_catalogue_language.meta_keyword',
            'attribute_catalogue_language.meta_description',
            'attribute_catalogue_language.canonical'
        ])->join('attribute_catalogue_language', 'attribute_catalogue_language.attribute_catalogue_id', '=', 'attribute_catalogues.id')->where('attribute_catalogue_language.language_id', '=', $language_id)->find($id);
    }

    public function getAll($languageId = 0, $notParent = false)
    {
        $query = $this->model
            ->join('attribute_catalogue_language', 'attribute_catalogues.id', '=', 'attribute_catalogue_language.attribute_catalogue_id')
            ->where('attribute_catalogue_language.language_id', $languageId);
        if ($notParent) {
            $query->whereRaw('attribute_catalogues.rgt - attribute_catalogues.lft = ?', [1]);
        }
        $query->orderBy('attribute_catalogue_language.name', 'ASC');
        return $query->select('attribute_catalogues.*')->get();
    }

    public function getAttributeCatalogueWhereIn($whereIn = [], $whereInField = 'id', $language = 1)
    {
        return $this->model->select([
            'attribute_catalogues.id',
            'attribute_catalogue_language.name',
        ])
            ->join('attribute_catalogue_language', 'attribute_catalogue_language.attribute_catalogue_id', '=', 'attribute_catalogues.id')
            ->where('attribute_catalogue_language.language_id', '=', $language)
            ->where([config('apps.general.publish')])
            ->whereIn($whereInField, $whereIn)
            ->get();
    }
}
