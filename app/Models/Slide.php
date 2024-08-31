<?php

namespace App\Models;

use App\Traits\QueryScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slide extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $fillable = [
        'name',
        'keyword',
        'description',
        'item',
        'setting',
        'short_code',
        'publish',
        'user_id',
        'deleted_at',
    ];

    protected $table = 'slides';

    /*
        - Khi lưu trữ: Nếu gán một mảng hoặc một đối tượng vào trường item hoặc setting, Laravel sẽ tự động chuyển đổi mảng hoặc đối tượng đó thành chuỗi JSON trước khi lưu vào cơ sở dữ liệu.
        - Khi truy xuất: Khi truy xuất giá trị của trường item hoặc setting từ cơ sở dữ liệu, Laravel sẽ tự động chuyển đổi chuỗi JSON trở lại thành một mảng hoặc một đối tượng PHP.
    */
    protected $casts = [
        'item' => 'json',
        'setting' => 'json',
    ];
}
