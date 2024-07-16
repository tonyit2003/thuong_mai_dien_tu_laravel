<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes; // SoftDeletes: phương thức delete() sẽ xóa mềm (không xóa dữ liệu trong mysql)

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'province_id',
        'district_id',
        'ward_id',
        'address',
        'birthday',
        'image',
        'description',
        'user_agent',
        'ip',
        'user_catalogue_id',
        'publish'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Tên bảng tương ứng trong mysql
    protected $table = 'users';
    // Thông báo khóa chính là cột 'code'
    protected $primaryKey = 'id';
    // khóa chính không tự động tăng
    public $incrementing = true;

    public function user_catalogues()
    {
        // quan hệ n --- 1: (class thiết lập mối quan hệ, khóa ngoại trong bảng users, khóa chính trong bảng user_catalogues)
        return $this->belongsTo(UserCatalogue::class, 'user_catalogue_id', 'id');
    }

    public function hasPermission($permissionCanonical)
    {
        // từ user => lấy được nhóm user => lấy được danh sách các quyền => kiểm tra $permissionCanonical có tồn tại trong các canonical từ danh sách các quyền đó không
        // truy vấn => không dùng hàm => không dùng user_catalogues(), permissions()
        return $this->user_catalogues->permissions->contains('canonical', $permissionCanonical);
    }
}
