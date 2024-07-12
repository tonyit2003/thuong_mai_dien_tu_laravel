<?php

namespace App\Models;

use App\Traits\QueryScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class PostCatalogue extends Model
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

    // Tên bảng tương ứng trong mysql
    protected $table = 'post_catalogues';

    // mối quan hệ n - n với bảng languages
    /*
    belongsToMany
    (
        lớp mà model hiện tại có quan hệ n - n,
        bảng trung gian,
        tên của cột trong bảng trung gian trỏ đến khóa chính của model hiện tại,
        tên của cột trong bảng trung gian trỏ đến khóa chính của model Language
    )
    withPivot() => các cột trong bảng trung gian để lấy khi truy vấn mối quan hệ này
    withTimestamps() => tự động thêm các cột created_at và updated_at vào bảng trung gian
    */
    public function languages()
    {
        return $this->belongsToMany(Language::class, 'post_catalogue_language', 'post_catalogue_id', 'language_id')->withPivot('name', 'canonical', 'mete_title', 'meta_keyword', 'meta_description', 'description', 'content')->withTimestamps();
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_catalogue_post', 'post_catalogue_id', 'post_id');
    }

    public function post_catalogue_language()
    {
        return $this->hasMany(PostCatalogueLanguage::class, 'post_catalogue_id', 'id');
    }

    // kiểm tra xem một đối tượng PostCatalogue có con hay không.
    public static function isNodeCheck($id = 0)
    {
        $postCatalogue = PostCatalogue::find($id);

        if ($postCatalogue->rgt - $postCatalogue->lft !== 1) {
            return false;
        }

        return true;
    }
}
