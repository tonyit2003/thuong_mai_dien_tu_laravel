<?php

namespace App\Repositories;

use App\Models\PostCatalogue;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface;

/**
 * Class PostCatalogueRepository
 * @package App\Repositories
 */
class PostCatalogueRepository extends BaseRepository implements PostCatalogueRepositoryInterface
{
    protected $model;

    public function __construct(PostCatalogue $postCatalogue)
    {
        $this->model = $postCatalogue;
        parent::__construct($this->model); //truyền model lên lớp cha
    }

    public function getPostCatalogueById($id = 0, $language_id = 0)
    {
        return $this->model->select([
            'post_catalogues.id',
            'post_catalogues.parent_id',
            'post_catalogues.image',
            'post_catalogues.icon',
            'post_catalogues.album',
            'post_catalogues.publish',
            'post_catalogues.follow',
            'post_catalogue_language.name',
            'post_catalogue_language.description',
            'post_catalogue_language.content',
            'post_catalogue_language.meta_title',
            'post_catalogue_language.meta_keyword',
            'post_catalogue_language.meta_description',
            'post_catalogue_language.canonical'
        ])->join('post_catalogue_language', 'post_catalogue_language.post_catalogue_id', '=', 'post_catalogues.id')->where('post_catalogue_language.language_id', '=', $language_id)->findOrFail($id);
    }
}
