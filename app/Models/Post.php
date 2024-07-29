<?php

namespace App\Models;

use App\Traits\QueryScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    // QueryScopes: là 1 trait chứa các phương thức scope
    // trait: được sử dụng để tái sử dụng các phương thức giữa các class mà không cần phải sử dụng kế thừa
    // trait: không phải là các class => không thể được khởi tạo
    use HasFactory, SoftDeletes, QueryScopes;

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
    withPivot() => các cột trong bảng trung gian để lấy khi truy vấn mối quan hệ này (chỉ có thể truy vấn các cột trong withPivot, nếu không có withPivot => chỉ truy vấn được 2 khóa ngoại)
    withTimestamps() => tự động điền hoặc cập nhật các giá trị trong các cột created_at và updated_at.
    */
    public function languages()
    {
        return $this->belongsToMany(Language::class, 'post_language', 'post_id', 'language_id')->withPivot(
            'name',
            'canonical',
            'meta_title',
            'meta_keyword',
            'meta_description',
            'description',
            'content'
        )->withTimestamps();;
    }

    public function post_catalogues()
    {
        return $this->belongsToMany(PostCatalogue::class, 'post_catalogue_post', 'post_id', 'post_catalogue_id');
    }
}
