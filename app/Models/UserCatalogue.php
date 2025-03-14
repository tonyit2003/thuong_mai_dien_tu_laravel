<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\QueryScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserCatalogue extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, QueryScopes; // SoftDeletes: phương thức delete() sẽ xóa mềm (không xóa dữ liệu trong mysql)

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'publish'
    ];

    // Tên bảng tương ứng trong mysql
    protected $table = 'user_catalogues';
    // Thông báo khóa chính là cột 'code'
    protected $primaryKey = 'id';
    // khóa chính không tự động tăng
    public $incrementing = true;
    protected $attributes = [
        'publish' => 1
    ];

    public function users()
    {
        // quan hệ 1 --- n: (class thiết lập mối quan hệ, khóa ngoại trong bảng users, khóa chính trong bảng user_catalogues)
        return $this->hasMany(User::class, 'user_catalogue_id', 'id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_catalogue_permission', 'user_catalogue_id', 'permission_id');
    }
}
