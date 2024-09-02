<?php

namespace App\Models;

use App\Traits\QueryScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
{
    use HasFactory, SoftDeletes, QueryScopes; // SoftDeletes: phương thức delete() sẽ xóa mềm (không xóa dữ liệu trong mysql)

    protected $fillable = [
        'name',
        'canonical',
        'publish',
        'user_id',
        'image',
        'description',
        'current'
    ];

    // Tên bảng tương ứng trong mysql
    protected $table = 'languages';

    // mối quan hệ n - n với bảng post_catalogues
    /*
    belongsToMany
    (
        lớp mà model hiện tại có quan hệ n - n,
        bảng trung gian,
        tên của cột trong bảng trung gian trỏ đến khóa chính của model hiện tại,
        tên của cột trong bảng trung gian trỏ đến khóa chính của model PostCatalogue
    )
    withPivot() => các cột trong bảng trung gian để lấy khi truy vấn mối quan hệ này (chỉ có thể truy vấn các cột trong withPivot, nếu không có withPivot => chỉ truy vấn được 2 khóa ngoại)
    withTimestamps() => tự động điền hoặc cập nhật các giá trị trong các cột created_at và updated_at.
    */
    public function post_catalogues()
    {
        return $this->belongsToMany(PostCatalogue::class, 'post_catalogue_language', 'language_id', 'post_catalogue_id')->withPivot('name', 'canonical', 'meta_title', 'meta_keyword', 'meta_description', 'description', 'content')->withTimestamps();
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_language', 'language_id', 'post_id')->withPivot('name', 'canonical', 'meta_title', 'meta_keyword', 'meta_description', 'description', 'content')->withTimestamps();
    }

    public function product_catalogues()
    {
        return $this->belongsToMany(ProductCatalogue::class, 'product_catalogue_language', 'language_id', 'product_catalogue_id')->withPivot('name', 'canonical', 'meta_title', 'meta_keyword', 'meta_description', 'description', 'content')->withTimestamps();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_language', 'language_id', 'product_id')->withPivot('name', 'canonical', 'meta_title', 'meta_keyword', 'meta_description', 'description', 'content')->withTimestamps();
    }

    public function attribute_catalogues()
    {
        return $this->belongsToMany(AttributeCatalogue::class, 'attribute_catalogue_language', 'language_id', 'attribute_catalogue_id')->withPivot('name', 'canonical', 'meta_title', 'meta_keyword', 'meta_description', 'description', 'content')->withTimestamps();
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'attribute_language', 'language_id', 'attribute_id')->withPivot('name', 'canonical', 'meta_title', 'meta_keyword', 'meta_description', 'description', 'content')->withTimestamps();
    }

    public function product_variants()
    {
        return $this->belongsToMany(ProductVariant::class, 'product_variant_language', 'language_id', 'product_variant_id')->withPivot('name')->withTimestamps();
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_language', 'language_id', 'menu_id')->withPivot('name', 'canonical')->withTimestamps();
    }
}
