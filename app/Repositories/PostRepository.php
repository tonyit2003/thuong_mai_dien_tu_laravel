<?php

namespace App\Repositories;

use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;

/**
 * Class PostsRepository
 * @package App\Repositories
 */
class PostRepository extends BaseRepository implements PostRepositoryInterface
{
    protected $model;

    public function __construct(Post $post)
    {
        $this->model = $post;
        parent::__construct($this->model); //truyền model lên lớp cha
    }

    public function getPostById($id = 0, $language_id = 0)
    {
        return $this->model->select([
            'posts.id',
            'posts.post_catalogue_id',
            'posts.image',
            'posts.icon',
            'posts.album',
            'posts.publish',
            'posts.follow',
            'post_language.name',
            'post_language.description',
            'post_language.content',
            'post_language.meta_title',
            'post_language.meta_keyword',
            'post_language.meta_description',
            'post_language.canonical'
        ])->join('post_language', 'post_language.post_id', '=', 'posts.id')->with('post_catalogues')->where('post_language.language_id', '=', $language_id)->findOrFail($id);
    }
}
