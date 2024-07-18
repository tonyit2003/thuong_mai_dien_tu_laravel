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
    withPivot() => các cột trong bảng trung gian để lấy khi truy vấn mối quan hệ này
    withTimestamps() => tự động thêm các cột created_at và updated_at vào bảng trung gian
    */
    public function languages()
    {
        return $this->belongsToMany(Language::class, 'post_language', 'post_id', 'language_id');
    }

    public function post_catalogues()
    {
        return $this->belongsToMany(PostCatalogue::class, 'post_catalogue_post', 'post_id', 'post_catalogue_id');
    }
}
