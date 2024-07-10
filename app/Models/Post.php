<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes; // SoftDeletes: phương thức delete() sẽ xóa mềm (không xóa dữ liệu trong mysql)

    protected $fillable = [
        'post_catalogue_id',
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
    protected $table = 'posts';

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
        return $this->belongsToMany(Language::class, 'post_language', 'post_id', 'language_id')->withPivot('name', 'canonical', 'mete_title', 'meta_keyword', 'meta_description', 'description', 'content')->withTimestamps();
    }

    public function post_catalogues()
    {
        return $this->belongsToMany(PostCatalogue::class, 'post_catalogue_post', 'post_id', 'post_catalogue_id');
    }
}
